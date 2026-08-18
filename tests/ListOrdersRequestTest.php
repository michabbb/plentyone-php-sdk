<?php

declare(strict_types=1);

namespace PlentyOne\Tests;

use PHPUnit\Framework\TestCase;
use PlentyOne\PlentyOneConnector;
use PlentyOne\Requests\Orders\ListOrdersRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Response;

class ListOrdersRequestTest extends TestCase
{
    public function testItHitsTheListEndpointAndNotTheSearchEndpoint(): void
    {
        $response = $this->send();

        self::assertSame('/orders', (new ListOrdersRequest())->resolveEndpoint());
        self::assertSame('/rest/orders', $response->getPsrRequest()->getUri()->getPath());
    }

    public function testItPassesFiltersThroughToTheQueryString(): void
    {
        $filters = [
            'createdAtFrom' => '2026-08-17T00:00:00+02:00',
            'createdAtTo'   => '2026-08-19T00:00:00+02:00',
            'itemsPerPage'  => 250,
            'page'          => 2,
        ];

        $response = $this->send($filters);

        self::assertSame($filters, $response->getPendingRequest()->query()->all());
        self::assertSame(
            [
                'createdAtFrom' => '2026-08-17T00:00:00+02:00',
                'createdAtTo'   => '2026-08-19T00:00:00+02:00',
                'itemsPerPage'  => '250',
                'page'          => '2',
            ],
            $this->queryOf($response),
        );
    }

    public function testItRemovesNullFiltersFromTheQuery(): void
    {
        $response = $this->send([
            'createdAtFrom' => '2026-08-17T00:00:00+02:00',
            'updatedAtFrom' => null,
            'page'          => null,
            'itemsPerPage'  => 50,
        ]);

        self::assertSame(
            ['createdAtFrom' => '2026-08-17T00:00:00+02:00', 'itemsPerPage' => 50],
            $response->getPendingRequest()->query()->all(),
        );
        self::assertArrayNotHasKey('updatedAtFrom', $this->queryOf($response));
        self::assertArrayNotHasKey('page', $this->queryOf($response));
    }

    public function testItSerialisesArrayFiltersAsBracketedQueryParameters(): void
    {
        $response = $this->send(['with' => ['orderItems.amounts', 'addresses']]);

        // PHP's http_build_query - which Saloon uses to build the URI - emits indexed
        // keys (`with[0]=`). PlentyONE accepts that just as well as `with[]=`.
        self::assertSame(
            'with%5B0%5D=orderItems.amounts&with%5B1%5D=addresses',
            $response->getPsrRequest()->getUri()->getQuery(),
        );
        self::assertSame(
            ['with' => ['orderItems.amounts', 'addresses']],
            $this->queryOf($response),
        );
    }

    public function testItReturnsTheResponseEnvelopeUnchanged(): void
    {
        $response = $this->send(['itemsPerPage' => 50], $this->envelope());

        self::assertSame(200, $response->status());
        self::assertSame(1179, $response->json('totalsCount'));
        self::assertFalse($response->json('isLastPage'));
        self::assertSame(24, $response->json('lastPageNumber'));
        self::assertCount(1, $response->json('entries'));
        self::assertSame(1478580, $response->json('entries.0.id'));
        // referrerId / statusId are decimals - the SDK must pass them through untouched.
        self::assertSame('4.01', $response->json('entries.0.referrerId'));
        self::assertSame('8.03', $response->json('entries.0.statusId'));
    }

    /**
     * @param  array<string,mixed>  $filters
     * @param  array<string,mixed>|null  $body
     */
    private function send(array $filters = [], ?array $body = null): Response
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'access_token'  => 'token',
                'refresh_token' => 'refresh-token',
                'expires_in'    => 3600,
            ]),
            MockResponse::make($body ?? $this->envelope()),
        ]);

        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mockClient);
        $connector->login('user', 'password');

        return $connector->orders()->list($filters);
    }

    /**
     * The response envelope of GET /rest/orders, shortened to a single entry.
     *
     * @return array<string,mixed>
     */
    private function envelope(): array
    {
        return [
            'page'           => 1,
            'totalsCount'    => 1179,
            'isLastPage'     => false,
            'lastPageNumber' => 24,
            'firstOnPage'    => 1,
            'lastOnPage'     => 50,
            'itemsPerPage'   => 50,
            'entries'        => [
                [
                    'id'         => 1478580,
                    'typeId'     => 1,
                    'referrerId' => '4.01',
                    'statusId'   => '8.03',
                    'createdAt'  => '2026-08-18T23:44:11+02:00',
                    'updatedAt'  => '2026-08-18T23:44:12+02:00',
                ],
            ],
        ];
    }

    /** @return array<array-key,mixed> */
    private function queryOf(Response $response): array
    {
        parse_str($response->getPsrRequest()->getUri()->getQuery(), $query);

        return $query;
    }
}
