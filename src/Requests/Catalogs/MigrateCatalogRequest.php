<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class MigrateCatalogRequest extends Request
{
    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/catalogs/catalogs/migrate';
    }
}