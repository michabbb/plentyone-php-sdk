<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class GetCatalogPreviewVdiRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $id,
        private readonly int $variationId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/catalogs/' . $this->id . '/preview/vdi';
    }

    protected function defaultBody(): array
    {
        return ['variationId' => $this->variationId];
    }
}