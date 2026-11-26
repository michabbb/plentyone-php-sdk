<?php

declare(strict_types=1);

namespace PlentyOne\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use PlentyOne\PlentyOneConnector;
use Saloon\Config;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class GetOrderItemsRequestTest extends TestCase
{
    protected function setUp(): void
    {
        Config::preventStrayRequests();
    }

    protected function tearDown(): void
    {
        Config::allowStrayRequests();
    }

    public function testItRequestsTheFirstPageSortedByIdAscending(): void
    {
        $response = $this->connector(MockResponse::make(['entries' => []]))->orders()->items(1000001, 1, 50);

        self::assertSame('GET', $response->getPsrRequest()->getMethod());
        self::assertSame('/rest/orders/1000001/items', $response->getPsrRequest()->getUri()->getPath());
        self::assertSame('page=1&itemsPerPage=50&sortBy=id&sortOrder=asc', $response->getPsrRequest()->getUri()->getQuery());
    }

    public function testItRequestsAFollowingPage(): void
    {
        $response = $this->connector(MockResponse::make(['entries' => []]))->orders()->items(1000001, 2, 50);

        self::assertSame('/rest/orders/1000001/items', $response->getPsrRequest()->getUri()->getPath());
        self::assertSame('page=2&itemsPerPage=50&sortBy=id&sortOrder=asc', $response->getPsrRequest()->getUri()->getQuery());
    }

    public function testItUsesTheApiDefaultsWhenPageAndSizeAreOmitted(): void
    {
        $response = $this->connector(MockResponse::make(['entries' => []]))->orders()->items(1000001);

        self::assertSame('page=1&itemsPerPage=50&sortBy=id&sortOrder=asc', $response->getPsrRequest()->getUri()->getQuery());
    }

    public function testItAcceptsThePageSizeBoundaries(): void
    {
        foreach ([1, 250] as $itemsPerPage) {
            $response = $this->connector(MockResponse::make(['entries' => []]))->orders()->items(1000001, 1, $itemsPerPage);

            self::assertSame(
                "page=1&itemsPerPage={$itemsPerPage}&sortBy=id&sortOrder=asc",
                $response->getPsrRequest()->getUri()->getQuery(),
            );
        }
    }

    public function testItReturnsThePaginationEnvelopeAndAmountsUnchanged(): void
    {
        $body = [
            'page'           => 2,
            'totalsCount'    => 3,
            'isLastPage'     => true,
            'lastPageNumber' => 2,
            'firstOnPage'    => 3,
            'lastOnPage'     => 3,
            'itemsPerPage'   => 2,
            'entries'        => [[
                'id'              => 900003,
                'orderId'         => 1000001,
                'typeId'          => 1,
                'itemVariationId' => 1234,
                'quantity'        => 5,
                'amounts'         => [[
                    'id'                 => 800003,
                    'orderItemId'        => 900003,
                    'isSystemCurrency'   => true,
                    'currency'           => 'EUR',
                    'exchangeRate'       => 1,
                    'priceOriginalGross' => 3.25,
                ]],
                'unknownFutureField' => ['kept' => true],
            ]],
        ];

        $response = $this->connector(MockResponse::make($body))->orders()->items(1000001, 2, 2);

        self::assertSame(200, $response->status());
        self::assertSame($body, $response->json());
    }

    /** @return array<string,array{int,int,int}> */
    public static function invalidArguments(): array
    {
        return [
            'zero order id'      => [0, 1, 50],
            'negative order id'  => [-1, 1, 50],
            'zero page'          => [1000001, 0, 50],
            'negative page'      => [1000001, -1, 50],
            'zero page size'     => [1000001, 1, 0],
            'page size over 250' => [1000001, 1, 251],
        ];
    }

    #[DataProvider('invalidArguments')]
    public function testItRejectsInvalidArgumentsWithoutSendingARequest(int $orderId, int $page, int $itemsPerPage): void
    {
        $mockClient = new MockClient([MockResponse::make(['entries' => []])]);
        $connector  = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mockClient);

        try {
            $connector->orders()->items($orderId, $page, $itemsPerPage);
            self::fail('Invalid arguments must be rejected.');
        } catch (InvalidArgumentException) {
            $mockClient->assertNothingSent();
        }
    }

    private function connector(MockResponse $response): PlentyOneConnector
    {
        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient(new MockClient([$response]));

        return $connector;
    }
}
