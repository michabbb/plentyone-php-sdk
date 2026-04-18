<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateCatalogContentRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        private readonly string $id,
        private readonly array $data = [],
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/catalogs/' . $this->id . '/content';
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}