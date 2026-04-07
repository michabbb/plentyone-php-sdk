<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Stock;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetStockRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<int,string>|null  $columns
     */
    public function __construct(
        private readonly ?int    $variationId = null,
        private readonly ?string $updatedAtFrom = null,
        private readonly ?string $updatedAtTo = null,
        private readonly ?int    $page = null,
        private readonly ?int    $itemsPerPage = null,
        private readonly ?array  $columns = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/stockmanagement/stock';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'variationId'   => $this->variationId,
            'updatedAtFrom' => $this->updatedAtFrom,
            'updatedAtTo'   => $this->updatedAtTo,
            'page'          => $this->page,
            'itemsPerPage'  => $this->itemsPerPage,
            'columns'       => $this->columns,
        ], fn ($value) => $value !== null);
    }
}
