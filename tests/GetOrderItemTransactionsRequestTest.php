<?php

declare(strict_types=1);

namespace PlentyOne\Tests;

use PHPUnit\Framework\TestCase;
use PlentyOne\PlentyOneConnector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\Response;

class GetOrderItemTransactionsRequestTest extends TestCase
{
    public function testItUsesTheTransactionsEndpointWithScalarPaginationParameters(): void
    {
        $response = $this->send(1486074, 2, 250);

        self::assertSame('GET', $response->getPsrRequest()->getMethod());
        self::assertSame('/rest/orders/items/transactions', $response->getPsrRequest()->getUri()->getPath());
        self::assertSame(
            'orderId=1486074&page=2&itemsPerPage=250',
            $response->getPsrRequest()->getUri()->getQuery(),
        );
    }

    public function testItOmitsPaginationParametersWhenTheyAreNotProvided(): void
    {
        $response = $this->send(1486074);

        self::assertSame('orderId=1486074', $response->getPsrRequest()->getUri()->getQuery());
    }

    private function send(int $orderId, ?int $page = null, ?int $itemsPerPage = null): Response
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'access_token'  => 'token',
                'refresh_token' => 'refresh-token',
                'expires_in'    => 3600,
            ]),
            MockResponse::make([
                'page'        => 1,
                'totalsCount' => 0,
                'entries'     => [],
            ]),
        ]);

        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mockClient);
        $connector->login('user', 'password');

        return $connector->orders()->itemTransactions($orderId, $page, $itemsPerPage);
    }
}
