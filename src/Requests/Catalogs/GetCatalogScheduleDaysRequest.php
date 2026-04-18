<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCatalogScheduleDaysRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/catalogs/catalogs/schedule/days';
    }
}