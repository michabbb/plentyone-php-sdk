<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class RestoreCatalogRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $id,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/catalogs/archive/' . $this->id . '/restore';
    }
}