<?php

declare(strict_types=1);

namespace PlentyOne;

use PlentyOne\Auth\PlentyOneAuthenticator;
use PlentyOne\Requests\Auth\LoginRequest;
use PlentyOne\Resources\ImagesResource;
use PlentyOne\Resources\VariationsResource;
use Saloon\Http\Connector;
use Saloon\Http\PendingRequest;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class PlentyOneConnector extends Connector
{
    use AlwaysThrowOnErrors;

    private ?string $username = null;
    private ?string $password = null;
    private ?PlentyOneAuthenticator $tokenAuth = null;

    public function __construct(
        private readonly string $baseUrl,
    ) {}

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    public function boot(PendingRequest $pendingRequest): void
    {
        if ($pendingRequest->getRequest() instanceof LoginRequest) {
            return;
        }

        if ($this->tokenAuth?->hasExpired() && $this->username && $this->password) {
            $this->login($this->username, $this->password);
        }
    }

    public function login(string $username, string $password): self
    {
        $this->username = $username;
        $this->password = $password;

        $response = $this->send(new LoginRequest($username, $password));
        $this->tokenAuth = PlentyOneAuthenticator::fromLoginResponse($response->json());
        $this->authenticate($this->tokenAuth);

        return $this;
    }

    public function variations(): VariationsResource
    {
        return new VariationsResource($this);
    }

    public function images(): ImagesResource
    {
        return new ImagesResource($this);
    }
}
