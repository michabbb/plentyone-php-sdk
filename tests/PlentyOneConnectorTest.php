<?php

declare(strict_types=1);

namespace PlentyOne\Tests;

use PHPUnit\Framework\TestCase;
use PlentyOne\PlentyOneConnector;
use PlentyOne\Requests\Variations\GetVariationRequest;
use Saloon\Exceptions\Request\Statuses\InternalServerErrorException;
use Saloon\Exceptions\Request\Statuses\UnauthorizedException;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use Saloon\Http\PendingRequest;

class PlentyOneConnectorTest extends TestCase
{
    public function testItUsesTheRenewedTokenForTheRequestThatDetectedExpiry(): void
    {
        $mockClient = new MockClient([
            MockResponse::make($this->loginResponse('expired-token', expiresIn: -1)),
            MockResponse::make($this->loginResponse('renewed-token')),
            static function (PendingRequest $pendingRequest): MockResponse {
                $authorization = $pendingRequest->headers()->get('Authorization');

                return 'Bearer renewed-token' === $authorization
                    ? MockResponse::make(['id' => 12973], 200)
                    : MockResponse::make(['error' => ['message' => 'Unauthenticated.']], 401);
            },
        ]);

        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mockClient);
        $connector->login('user', 'password');

        $response = $connector->send(new GetVariationRequest(662028, 12973));

        self::assertSame(200, $response->status());
        self::assertSame(12973, $response->json('id'));
    }

    public function testItLogsInAgainAndRetriesOnceWhenPlentyRejectsAValidToken(): void
    {
        $mockClient = new MockClient([
            MockResponse::make($this->loginResponse('initial-token')),
            MockResponse::make(['error' => ['message' => 'Unauthenticated.']], 401),
            MockResponse::make($this->loginResponse('replacement-token')),
            static function (PendingRequest $pendingRequest): MockResponse {
                $authorization = $pendingRequest->headers()->get('Authorization');

                return 'Bearer replacement-token' === $authorization
                    ? MockResponse::make(['id' => 12973], 200)
                    : MockResponse::make(['error' => ['message' => 'Unauthenticated.']], 401);
            },
        ]);

        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mockClient);
        $connector->login('user', 'password');

        $response = $connector->send(new GetVariationRequest(662028, 12973));

        self::assertSame(200, $response->status());
        self::assertSame(12973, $response->json('id'));
    }

    public function testItDoesNotRetryNonAuthenticationFailures(): void
    {
        $mockClient = new MockClient([
            MockResponse::make($this->loginResponse('valid-token')),
            MockResponse::make(['error' => ['message' => 'Server error.']], 500),
        ]);

        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mockClient);
        $connector->login('user', 'password');

        $this->expectException(InternalServerErrorException::class);

        $connector->send(new GetVariationRequest(662028, 12973));
    }

    public function testItThrowsWhenTheRequestIsStillUnauthorizedAfterRelogin(): void
    {
        $mockClient = new MockClient([
            MockResponse::make($this->loginResponse('initial-token')),
            MockResponse::make(['error' => ['message' => 'Unauthenticated.']], 401),
            MockResponse::make($this->loginResponse('replacement-token')),
            MockResponse::make(['error' => ['message' => 'Unauthenticated.']], 401),
        ]);

        $connector = new PlentyOneConnector('https://plenty.example.test/rest');
        $connector->withMockClient($mockClient);
        $connector->login('user', 'password');

        $this->expectException(UnauthorizedException::class);

        $connector->send(new GetVariationRequest(662028, 12973));
    }

    /** @return array{access_token: string, refresh_token: string, expires_in: int} */
    private function loginResponse(string $accessToken, int $expiresIn = 3600): array
    {
        return [
            'access_token'  => $accessToken,
            'refresh_token' => 'refresh-token',
            'expires_in'    => $expiresIn,
        ];
    }
}
