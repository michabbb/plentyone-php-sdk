<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Stock;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetWarehousesRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<int,string>|null  $with  Related objects to load. 'repairWarehouse' is the only relation currently available.
     */
    public function __construct(
        private readonly ?array $with = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/stockmanagement/warehouses';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'with' => $this->with,
        ], fn ($value) => $value !== null);
    }
}
