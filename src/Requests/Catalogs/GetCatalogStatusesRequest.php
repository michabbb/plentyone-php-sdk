<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCatalogStatusesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly ?string $catalogId = null,
        private readonly ?int $page = null,
        private readonly ?int $itemsPerPage = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/statuses';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'catalogId' => $this->catalogId,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
        ], fn($value) => $value !== null);
    }
}