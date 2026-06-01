<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Stock\GetStockRequest;
use PlentyOne\Requests\Stock\GetWarehousesRequest;
use PlentyOne\Requests\Stock\RedistributeStockRequest;
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
     * @param  array<int,string>|null  $with  Related objects to load. 'repairWarehouse' is the only relation currently available.
     *
     * @see https://developers.plentymarkets.com/en-gb/plentymarkets-rest-api/index.html#/Stock/get_rest_stockmanagement_warehouses
     */
    public function warehouses(?array $with = null): Response
    {
        return $this->connector->send(new GetWarehousesRequest($with));
    }

    /**
     * Redistribute ("umbuchen") stock between warehouses / storage locations.
     *
     * Pass one or more redistribution objects. Each entry typically contains
     * variationId, reasonId, quantity and a source/target pair
     * (currentWarehouseId → newWarehouseId and/or currentStorageLocationId → newStorageLocationId).
     * Batch-managed or BBD/MHD variations must additionally include `batch` / `bestBeforeDate`.
     *
     * @param  array<int,array<string,mixed>>  $redistributions  List of redistribution objects.
     *
     * @see https://developers.plentymarkets.com/en-gb/plentymarkets-rest-api/index.html#/Stock/put_rest_stockmanagement_stock_redistribute
     */
    public function redistribute(array $redistributions): Response
    {
        return $this->connector->send(new RedistributeStockRequest($redistributions));
    }

    /**
     * Convenience: move a single quantity of a variation from one warehouse to another.
     *
     * @param  int|null     $reasonId                 Movement reason (Bewegungsgrund), if required by your config.
     * @param  int|null     $currentStorageLocationId Source storage location (optional).
     * @param  int|null     $newStorageLocationId     Target storage location (optional).
     * @param  string|null  $batch                    Required for batch-managed variations.
     * @param  string|null  $bestBeforeDate           Required for variations with a BBD/MHD.
     */
    public function moveBetweenWarehouses(
        int     $variationId,
        int     $currentWarehouseId,
        int     $newWarehouseId,
        int     $quantity,
        ?int    $reasonId = null,
        ?int    $currentStorageLocationId = null,
        ?int    $newStorageLocationId = null,
        ?string $batch = null,
        ?string $bestBeforeDate = null,
    ): Response {
        return $this->redistribute([
            [
                'variationId'              => $variationId,
                'currentWarehouseId'       => $currentWarehouseId,
                'newWarehouseId'           => $newWarehouseId,
                'quantity'                 => $quantity,
                'reasonId'                 => $reasonId,
                'currentStorageLocationId' => $currentStorageLocationId,
                'newStorageLocationId'     => $newStorageLocationId,
                'batch'                    => $batch,
                'bestBeforeDate'           => $bestBeforeDate,
            ],
        ]);
    }
}
