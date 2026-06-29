<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCatalogsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected ?int $page = null,
        protected ?int $itemsPerPage = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/catalogs';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'page'         => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
        ], static fn ($v): bool => $v !== null);
    }
}
