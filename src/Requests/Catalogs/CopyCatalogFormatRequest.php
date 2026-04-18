<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CopyCatalogFormatRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        private readonly string $catalogId,
        private readonly array $data = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/catalogs/' . $this->catalogId . '/copy';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}