<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCatalogVersionsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/catalogs/' . $this->id . '/versions';
    }
}
