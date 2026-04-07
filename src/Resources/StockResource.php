<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Stock\GetStockRequest;
use PlentyOne\Requests\Stock\GetWarehousesRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class StockResource extends BaseResource
{
    /**
     * List stock entries across all warehouses with optional filters.
     *
     * @param  array<int,string>|null  $columns  Limit returned columns (e.g. ['warehouseId','stockNet'])
     *
     * @see https://developers.plentymarkets.com/en-gb/plentymarkets-rest-api/index.html#/Stock/get_rest_stockmanagement_stock
     */
    public function list(
        ?int    $variationId = null,
        ?string $updatedAtFrom = null,
        ?string $updatedAtTo = null,
        ?int    $page = null,
        ?int    $itemsPerPage = null,
        ?array  $columns = null,
    ): Response {
        return $this->connector->send(new GetStockRequest(
            $variationId,
            $updatedAtFrom,
            $updatedAtTo,
            $page,
            $itemsPerPage,
            $columns,
        ));
    }

    /**
     * Convenience: list stock entries for a single variation.
     */
    public function forVariation(int $variationId): Response
    {
        return $this->connector->send(new GetStockRequest(variationId: $variationId));
    }

    /**
     * List all warehouses (id, name, type, …).
     *
     * @see https://developers.plentymarkets.com/en-gb/plentymarkets-rest-api/index.html#/Stock/get_rest_stockmanagement_warehouses
     */
    public function warehouses(): Response
    {
        return $this->connector->send(new GetWarehousesRequest());
    }
}
