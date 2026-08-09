<?php

declare(strict_types=1);

namespace PlentyOne\Auth;

use DateTimeImmutable;
use Saloon\Contracts\Authenticator;
use Saloon\Http\PendingRequest;

class PlentyOneAuthenticator implements Authenticator
{
    /**
     * Safety margin treated as "already expired" before the token actually runs out.
     *
     * Without it, a token that is still valid for a fraction of a second passes the
     * check in PlentyOneConnector::boot(), but expires while the request is still in
     * flight — PlentyONE then answers 401 Unauthenticated. The margin also absorbs
     * that expiresAt is computed locally *after* the login response arrived, while
     * PlentyONE issued the token before the network round-trip back, which makes the
     * locally stored lifetime slightly too optimistic.
     */
    public const EXPIRY_SKEW_SECONDS = 30;

    public function __construct(
        public readonly string            $accessToken,
        public readonly string            $refreshToken,
        public readonly DateTimeImmutable $expiresAt,
    ) {}

    public function set(PendingRequest $pendingRequest): void
    {
        $pendingRequest->headers()->add('Authorization', 'Bearer ' . $this->accessToken);
    }

    /**
     * Reports the token as expired EXPIRY_SKEW_SECONDS before it actually expires,
     * so a renewal happens while the current token is still accepted by PlentyONE.
     */
    public function hasExpired(): bool
    {
        return new DateTimeImmutable() >= $this->expiresAt->modify('-' . self::EXPIRY_SKEW_SECONDS . ' seconds');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromLoginResponse(array $data): self
    {
        return new self(
            accessToken: $data['access_token'],
            refreshToken: $data['refresh_token'],
            expiresAt: (new DateTimeImmutable())->modify('+' . $data['expires_in'] . ' seconds'),
        );
    }
}
