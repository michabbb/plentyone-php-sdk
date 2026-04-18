<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CheckCatalogConnectionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $protocol,
        private readonly array $data = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/connection/check/' . $this->protocol;
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}