<?php

declare(strict_types=1);

namespace PlentyOne\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use PlentyOne\PlentyOneConnector;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use UnexpectedValueException;

class ReordersResourceTest extends TestCase
{
    public function testItCreatesHeaderAndAllItemsThenLinksATag(): void
    {
        $payload = [
            'typeId'     => 1,
            'statusId'   => 19,
            'plentyId'   => 1000,
            'ownerId'    => 10,
            'referrerId' => 0,
            'relations'  => [
                ['referenceType' => 'contact', 'referenceId' => 200, 'relation' => 'sender'],
                ['referenceType' => 'warehouse', 'referenceId' => 30, 'relation' => 'receiver'],
            ],
            'dates' => [
                ['typeId' => 7, 'date' => '2026-11-02T00:00:00+01:00'],
                ['typeId' => 11, 'date' => '2026-10-15T00:00:00+02:00'],
            ],
            'properties' => [['typeId' => 6, 'value' => 'de']],
            'orderItems' => [
                [
                    'typeId'          => 1,
                    'itemVariationId' => 100,
                    'quantity'        => 2,
                    'orderItemName'   => 'Example item',
                    'attributeValues' => '',
                    'position'        => 0,
                    'properties'      => [['typeId' => 21, 'value' => '1']],
                    'dates'           => [['typeId' => 11, 'date' => '2026-10-15T00:00:00+02:00']],
                    'amounts'         => [[
                        'currency' => 'EUR', 'exchangeRate' => 1, 'priceOriginalGross' => 12.64,
                        'discount' => 0, 'surcharge' => 0, 'isPercentage' => false,
                    ]],
                ],
                ['typeId' => 1, 'itemVariationId' => 101, 'quantity' => 1],
            ],
        ];
        $created = ['id' => 12345, ...$payload, 'typeId' => 12];
        $mock    = new MockClient([
            MockResponse::make($created),
            MockResponse::make(['tagId' => 227, 'relationshipValue' => 12345]),
        ]);
        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mock);

        $response = $connector->reorders()->create($payload);

        self::assertSame('POST', $response->getPsrRequest()->getMethod());
        self::assertSame('/rest/reorders', $response->getPsrRequest()->getUri()->getPath());
        self::assertSame([...$payload, 'typeId' => 12], json_decode((string) $response->getPsrRequest()->getBody(), true, flags: JSON_THROW_ON_ERROR));
        self::assertSame($created, $response->json());
        $mock->assertSentCount(1);

        $tag = $connector->tags()->link(227, 'order', (int) $response->json('id'));

        self::assertSame('POST', $tag->getPsrRequest()->getMethod());
        self::assertSame('/rest/tags/relationships', $tag->getPsrRequest()->getUri()->getPath());
        self::assertSame([
            'tagId' => 227, 'tagType' => 'order', 'relationshipValue' => 12345,
        ], json_decode((string) $tag->getPsrRequest()->getBody(), true, flags: JSON_THROW_ON_ERROR));
        $mock->assertSentCount(2);
    }

    public function testCreateRequiresAnExplicitNonNullStatusBeforeSending(): void
    {
        foreach ([[], ['statusId' => null]] as $payload) {
            $mock      = new MockClient([]);
            $connector = new PlentyOneConnector('https://plenty.example.test/rest');
            $connector->withMockClient($mock);

            try {
                $connector->reorders()->create($payload);
                self::fail('Creating without an explicit status must fail.');
            } catch (InvalidArgumentException $exception) {
                self::assertStringContainsString('statusId', $exception->getMessage());
            }

            $mock->assertNothingSent();
        }
    }

    public function testCreateKeepsTheChosenStatusAndDefaultsToType12(): void
    {
        $response = $this->connector(MockResponse::make(['id' => 12345]))->reorders()->create(['statusId' => 19.2]);

        self::assertSame(['statusId' => 19.2, 'typeId' => 12], json_decode((string) $response->getPsrRequest()->getBody(), true, flags: JSON_THROW_ON_ERROR));
    }

    public function testItUpdatesOnlyTheSuppliedFields(): void
    {
        $payload = [
            'dates'      => [['typeId' => 11, 'date' => '2026-10-20T00:00:00+02:00']],
            'orderItems' => [['id' => 101, 'quantity' => 3]],
        ];
        $response = $this->connector(MockResponse::make(['id' => 12345]))->reorders()->update(12345, $payload);

        self::assertSame('PUT', $response->getPsrRequest()->getMethod());
        self::assertSame('/rest/reorders/12345', $response->getPsrRequest()->getUri()->getPath());
        self::assertSame($payload, json_decode((string) $response->getPsrRequest()->getBody(), true, flags: JSON_THROW_ON_ERROR));
    }

    public function testWriteErrorsAreNotSwallowedOrRetried(): void
    {
        foreach (['create', 'update'] as $method) {
            $mock      = new MockClient([MockResponse::make(['error' => 'Invalid data'], 422)]);
            $connector = new PlentyOneConnector('https://plenty.example.test/rest');
            $connector->withMockClient($mock);

            try {
                if ($method === 'create') {
                    $connector->reorders()->create(['statusId' => 19]);
                } else {
                    $connector->reorders()->update(12345, ['statusId' => 19]);
                }
                self::fail('API errors must propagate.');
            } catch (RequestException $exception) {
                self::assertSame(422, $exception->getResponse()->status());
            }

            $mock->assertSentCount(1);
        }
    }

    public function testItLoadsNestedReorderDataWithoutDroppingPositionsOrFields(): void
    {
        $body = [
            'id'         => 12345,
            'typeId'     => 12,
            'statusId'   => 19.1,
            'dates'      => [['typeId' => 7, 'date' => '2026-11-02T00:00:00+01:00']],
            'orderItems' => [
                ['id' => 101, 'quantity' => 2, 'amounts' => [['priceOriginalNet' => 12.64, 'discount' => 5]]],
                ['id' => 102, 'quantity' => 1, 'transactions' => [['quantity' => 1, 'direction' => 'in']]],
            ],
            'customApiField' => ['preserved' => true],
        ];
        $response = $this->connector(MockResponse::make($body))->reorders()->get(12345);

        self::assertSame('GET', $response->getPsrRequest()->getMethod());
        self::assertSame('/rest/orders/12345', $response->getPsrRequest()->getUri()->getPath());
        self::assertSame($body, $response->json());

        parse_str($response->getPsrRequest()->getUri()->getQuery(), $query);
        self::assertIsArray($query['with']);
        foreach (['orderItems.amounts', 'orderItems.variation', 'orderItems.transactions', 'dates', 'properties', 'tags', 'documents', 'comments', 'contactSender', 'warehouseReceiver'] as $relation) {
            self::assertContains($relation, $query['with']);
        }
    }

    public function testItAllowsReplacingAndDisablingDefaultRelations(): void
    {
        foreach ([['orderItems.amounts'], []] as $with) {
            $response = $this->connector(MockResponse::make(['typeId' => 12]))->reorders()->get(12345, $with);

            self::assertSame(['with' => $with], $response->getPendingRequest()->query()->all());
        }
    }

    public function testItRejectsAnOrderOfADifferentType(): void
    {
        $connector = $this->connector(MockResponse::make(['id' => 12345, 'typeId' => 1]));

        $this->expectException(UnexpectedValueException::class);
        $connector->reorders()->get(12345);
    }

    public function testItPreservesHttpErrors(): void
    {
        $connector = $this->connector(MockResponse::make(['error' => 'Not found'], 404));

        $this->expectException(RequestException::class);
        $connector->reorders()->get(12345);
    }

    public function testSearchEnforcesReordersAndPreservesPagination(): void
    {
        $body     = ['entries' => [['id' => 12345, 'typeId' => 12]], 'isLastPage' => false, 'lastPageNumber' => 3];
        $response = $this->connector(MockResponse::make($body))->reorders()->search([
            'orderTypeId'  => 1,
            'page'         => 2,
            'itemsPerPage' => 50,
            'statusId'     => 19.1,
        ]);

        self::assertSame('GET', $response->getPsrRequest()->getMethod());
        self::assertSame('/rest/orders/search', $response->getPsrRequest()->getUri()->getPath());
        self::assertSame($body, $response->json());
        $query = $response->getPendingRequest()->query()->all();
        self::assertSame(12, $query['orderTypeId']);
        self::assertSame(2, $query['page']);
        self::assertSame(50, $query['itemsPerPage']);
        self::assertSame(19.1, $query['statusId']);
        self::assertContains('orderItems.amounts', $query['with']);
        self::assertContains('orderItems.transactions', $query['with']);
    }

    public function testSearchPreservesExplicitRelationSelection(): void
    {
        $response = $this->connector(MockResponse::make(['entries' => []]))->reorders()->search(['with' => []]);

        self::assertSame(['with' => [], 'orderTypeId' => 12], $response->getPendingRequest()->query()->all());
    }

    private function connector(MockResponse $response): PlentyOneConnector
    {
        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient(new MockClient([$response]));

        return $connector;
    }
}
