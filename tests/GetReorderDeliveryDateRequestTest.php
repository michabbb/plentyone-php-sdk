<?php

declare(strict_types=1);

namespace PlentyOne\Tests;

use PHPUnit\Framework\TestCase;
use PlentyOne\PlentyOneConnector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class GetReorderDeliveryDateRequestTest extends TestCase
{
    public function testItUsesTheReorderDeliveryDateEndpoint(): void
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'access_token'  => 'token',
                'refresh_token' => 'refresh-token',
                'expires_in'    => 3600,
            ]),
            MockResponse::make(['deliveryDate' => '2026-09-06T12:00:00+02:00']),
        ]);

        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mockClient);
        $connector->login('user', 'password');

        $response = $connector->reorders()->deliveryDate(1486074);

        self::assertSame('GET', $response->getPsrRequest()->getMethod());
        self::assertSame('/rest/reorders/1486074/delivery_date', $response->getPsrRequest()->getUri()->getPath());
        self::assertSame('', $response->getPsrRequest()->getUri()->getQuery());
    }
}
