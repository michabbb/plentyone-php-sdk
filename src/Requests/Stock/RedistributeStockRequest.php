<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Stock;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Redistribute (rebook / "umbuchen") stock from one warehouse or storage location
 * to one or more others. The total stock stays the same – only the location changes.
 *
 * Each redistribution entry supports:
 *   - variationId               (int)    which variation to move
 *   - reasonId                  (int)    movement reason / Bewegungsgrund
 *   - quantity                  (int)    amount to move
 *   - currentWarehouseId        (int)    source warehouse
 *   - newWarehouseId            (int)    target warehouse
 *   - currentStorageLocationId  (int)    source storage location (optional)
 *   - newStorageLocationId      (int)    target storage location (optional)
 *   - batch                     (string) required if the variation is batch-managed
 *   - bestBeforeDate            (string) required if the variation has a BBD/MHD
 *
 * Note: For variations with Batch or BBD, those fields must be included in the
 * redistribution object.
 *
 * @see https://developers.plentymarkets.com/en-gb/plentymarkets-rest-api/index.html#/Stock/put_rest_stockmanagement_stock_redistribute
 */
class RedistributeStockRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<int,array<string,mixed>>  $redistributions  List of redistribution objects.
     */
    public function __construct(
        private readonly array $redistributions,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/stockmanagement/stock/redistribute';
    }

    /**
     * @return array<string,array<int,array<string,mixed>>>
     */
    protected function defaultBody(): array
    {
        return [
            'redistributions' => array_map(
                static fn (array $entry): array => array_filter(
                    $entry,
                    static fn ($value) => $value !== null,
                ),
                array_values($this->redistributions),
            ),
        ];
    }
}
