<?php

declare(strict_types=1);

namespace PlentyOne\Tests;

use PHPUnit\Framework\TestCase;
use PlentyOne\PlentyOneConnector;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class UpdateItemDescriptionRequestTest extends TestCase
{
    public function testItGetsTheItemDescriptionForOneLanguage(): void
    {
        $description = '<p><strong>Würziger Langtext</strong></p><br /><p>Unverändert.</p>';
        $connector   = $this->connector([
            'id'          => 123,
            'itemId'      => 673376,
            'lang'        => 'de',
            'description' => $description,
        ]);

        $response = $connector->items()->getDescription(673376, 24420);

        self::assertSame('GET', $response->getPsrRequest()->getMethod());
        self::assertSame(
            '/rest/items/673376/variations/24420/descriptions/de',
            $response->getPsrRequest()->getUri()->getPath(),
        );
        self::assertSame('', $response->getPsrRequest()->getUri()->getQuery());
        self::assertSame($description, $response->json('description'));
    }

    public function testItUpdatesOnlyTheItemDescriptionForOneLanguage(): void
    {
        $description = '<p><strong>Neue Würze</strong></p><br /><p>Größe bleibt UTF-8.</p>';
        $connector   = $this->connector([
            'id'          => 123,
            'itemId'      => 673376,
            'lang'        => 'de',
            'description' => $description,
        ]);

        $response = $connector->items()->updateDescription(
            673376,
            24420,
            $description,
        );

        self::assertSame('PUT', $response->getPsrRequest()->getMethod());
        self::assertSame(
            '/rest/items/673376/variations/24420/descriptions/de',
            $response->getPsrRequest()->getUri()->getPath(),
        );
        self::assertSame(
            ['description' => $description],
            json_decode((string) $response->getPsrRequest()->getBody(), true, flags: JSON_THROW_ON_ERROR),
        );
    }

    /** @param array<string, mixed> $description */
    private function connector(array $description): PlentyOneConnector
    {
        $mockClient = new MockClient([
            MockResponse::make([
                'access_token'  => 'token',
                'refresh_token' => 'refresh-token',
                'expires_in'    => 3600,
            ]),
            MockResponse::make($description),
        ]);

        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mockClient);
        $connector->login('user', 'password');

        return $connector;
    }
}
