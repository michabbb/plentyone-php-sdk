<?php

declare(strict_types=1);

namespace PlentyOne;

use PlentyOne\Auth\PlentyOneAuthenticator;
use PlentyOne\Requests\Auth\LoginRequest;
use PlentyOne\Resources\AccountsResource;
use PlentyOne\Resources\CatalogsResource;
use PlentyOne\Resources\CategoriesResource;
use PlentyOne\Resources\ImagesResource;
use PlentyOne\Resources\ItemsResource;
use PlentyOne\Resources\ManufacturersResource;
use PlentyOne\Resources\PropertiesResource;
use PlentyOne\Resources\ReferrersResource;
use PlentyOne\Resources\ShippingResource;
use PlentyOne\Resources\StockResource;
use PlentyOne\Resources\TagsResource;
use PlentyOne\Resources\VariationsResource;
use PlentyOne\Resources\WebstoresResource;
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
    ) {
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
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

        $response        = $this->send(new LoginRequest($username, $password));
        $this->tokenAuth = PlentyOneAuthenticator::fromLoginResponse($response->json());
        $this->authenticate($this->tokenAuth);

        return $this;
    }

    public function variations(): VariationsResource
    {
        return new VariationsResource($this);
    }

    public function items(): ItemsResource
    {
        return new ItemsResource($this);
    }

    public function images(): ImagesResource
    {
        return new ImagesResource($this);
    }

    public function categories(): CategoriesResource
    {
        return new CategoriesResource($this);
    }

    public function properties(): PropertiesResource
    {
        return new PropertiesResource($this);
    }

    public function webstores(): WebstoresResource
    {
        return new WebstoresResource($this);
    }

    public function referrers(): ReferrersResource
    {
        return new ReferrersResource($this);
    }

    public function catalogs(): CatalogsResource
    {
        return new CatalogsResource($this);
    }

    public function shipping(): ShippingResource
    {
        return new ShippingResource($this);
    }

    public function tags(): TagsResource
    {
        return new TagsResource($this);
    }

    public function manufacturers(): ManufacturersResource
    {
        return new ManufacturersResource($this);
    }

    public function accounts(): AccountsResource
    {
        return new AccountsResource($this);
    }

    public function stock(): StockResource
    {
        return new StockResource($this);
    }
}
