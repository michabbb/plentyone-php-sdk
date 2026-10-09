<?php

declare(strict_types=1);

namespace PlentyOne\Tests;

use PHPUnit\Framework\TestCase;
use PlentyOne\PlentyOneConnector;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Auth\TokenAuthenticator;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

class InitiateCatalogExportRequestTest extends TestCase
{
    public function testItStartsExportWithAnEmptyBodyAndPreservesTheEmptySuccessResponse(): void
    {
        $mock      = new MockClient([MockResponse::make('', 200)]);
        $connector = $this->connector($mock);
        $connector->authenticate(new TokenAuthenticator('test-token'));

        $response = $connector->catalogs()->initiateExport('11111111-2222-4333-8444-555555555555');
        $request  = $response->getPsrRequest();

        self::assertSame('POST', $request->getMethod());
        self::assertSame('/rest/catalogs/export/11111111-2222-4333-8444-555555555555/initiate', $request->getUri()->getPath());
        self::assertSame('', $request->getUri()->getQuery());
        self::assertSame('', (string) $request->getBody());
        self::assertSame('Bearer test-token', $request->getHeaderLine('Authorization'));
        self::assertSame(200, $response->status());
        self::assertSame('', $response->body());
        $mock->assertSentCount(1);
    }

    public function testItPropagatesApiErrorsWithoutRetryingTheExport(): void
    {
        foreach ([403, 404, 422, 500] as $status) {
            $mock = new MockClient([MockResponse::make('', $status)]);

            try {
                $this->connector($mock)->catalogs()->initiateExport('catalog-id');
                self::fail('API errors must propagate.');
            } catch (RequestException $exception) {
                self::assertSame($status, $exception->getResponse()->status());
                self::assertSame('', $exception->getResponse()->body());
            }

            $mock->assertSentCount(1);
        }
    }

    public function testExportStillDownloadsTheDefinitionWithoutStartingDataGeneration(): void
    {
        $definition = ['name' => 'Example catalog', 'data' => []];
        $mock       = new MockClient([MockResponse::make($definition)]);

        $response = $this->connector($mock)->catalogs()->export('catalog-id');

        self::assertSame('GET', $response->getPsrRequest()->getMethod());
        self::assertSame('/rest/catalogs/catalogs/catalog-id/export', $response->getPsrRequest()->getUri()->getPath());
        self::assertSame($definition, $response->json());
        $mock->assertSentCount(1);
    }

    private function connector(MockClient $mock): PlentyOneConnector
    {
        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mock);

        return $connector;
    }
}
