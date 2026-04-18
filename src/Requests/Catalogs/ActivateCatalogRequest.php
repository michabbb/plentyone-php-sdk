<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class ActivateCatalogRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $id,
        private readonly bool $active = true,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/catalogs/activate/' . $this->id;
    }

    protected function defaultBody(): array
    {
        return ['active' => $this->active];
    }
}