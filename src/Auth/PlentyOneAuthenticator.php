<?php

declare(strict_types=1);

namespace PlentyOne\Auth;

use DateTimeImmutable;
use Saloon\Contracts\Authenticator;
use Saloon\Http\PendingRequest;

class PlentyOneAuthenticator implements Authenticator
{
    public function __construct(
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly DateTimeImmutable $expiresAt,
    ) {}

    public function set(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add('Authorization', 'Bearer ' . $this->accessToken);
    }

    public function hasExpired(): bool
    {
        return new DateTimeImmutable() >= $this->expiresAt;
    }

    public static function fromLoginResponse(array $data): self
    {
        return new self(
            accessToken: $data['access_token'],
            refreshToken: $data['refresh_token'],
            expiresAt: (new DateTimeImmutable())->modify('+' . $data['expires_in'] . ' seconds'),
        );
    }
}
