<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Orders\GetOrderItemTransactionsRequest;
use PlentyOne\Requests\Orders\GetOrderRequest;
use PlentyOne\Requests\Orders\GetOrderStatusesRequest;
use PlentyOne\Requests\Orders\ListOrdersRequest;
use PlentyOne\Requests\Orders\SearchOrdersRequest;
use PlentyOne\Requests\Orders\UpdateOrderRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class OrdersResource extends BaseResource
{
    /**
     * List orders (paginated) — the only order endpoint that supports date *ranges*.
     *
     * GET /rest/orders
     *
     * Prefer this over `search()` whenever orders have to be selected by a time span:
     * `search()` filters `createdAt` / `updatedAt` for **equality only**, `list()` can do
     * ranges. Use `createdAtFrom`/`createdAtTo` for a backfill and `updatedAtFrom`/
     * `updatedAtTo` for incremental syncs, so that later changes to old orders
     * (cancellations, returns) are picked up.
     *
     * Verified filters: `createdAtFrom`, `createdAtTo`, `updatedAtFrom`, `updatedAtTo`
     * (all W3C date/time **including** timezone, e.g. `2026-08-17T00:00:00+02:00` — a date
     * without timezone is rejected with HTTP 422), `page`, `itemsPerPage` (default 50,
     * **maximum 250**) and `with` (array, e.g. `['orderItems.amounts']`).
     *
     * Both range bounds are **inclusive**, so consecutive slices may return a boundary
     * order twice, but never drop one.
     *
     * Pagination cap: `page * itemsPerPage` must not exceed **60,000** (HTTP 422 beyond
     * that) — the same cap as `search()`. Larger result sets have to be split into smaller
     * time slices by the caller; the SDK does not enforce or work around this.
     *
     * `orderTypeId` and `withDeleted` are **silently ignored** by this endpoint (HTTP 200,
     * unchanged result count). Use `search()` for those, or filter `typeId` client-side.
     *
     * @param  array<string,mixed>  $filters
     *
     * @see https://developers.plentymarkets.com/en-gb/developers/main/rest-api-guides/order-data.html
     */
    public function list(array $filters = []): Response
    {
        return $this->connector->send(new ListOrdersRequest($filters));
    }

    /**
     * Search orders with filters (paginated).
     *
     * GET /rest/orders/search
     *
     * Common filters: `orderId`, `plentyId`, `orderTypeId`, `statusId`, `referrerId`,
     * `ownerId`, `locationId`, `createdAt`, `updatedAt`, `contactData`, `itemVariationId`,
     * `variationNumber`, `documentNumber`, `tag`, `shippingStatus`, `sortBy`, `sortOrder`,
     * `page`, `itemsPerPage`, `with` (array), `lazyLoaded`, `withDeleted`.
     *
     * Order type IDs: 1 = sales order, 2 = delivery, 3 = returns, 4 = credit note,
     * 5 = warranty, 6 = repair, 7 = offer, 8 = advance order, 9 = multi-order,
     * 10 = multi credit note, 11 = multi delivery, 12 = reorder, 13 = partial delivery.
     *
     * @param  array<string,mixed>  $filters
     *
     * @see https://developers.plentymarkets.com/en-gb/developers/main/rest-api-guides/order-data.html
     */
    public function search(array $filters = []): Response
    {
        return $this->connector->send(new SearchOrdersRequest($filters));
    }

    /**
     * Get a single order by ID.
     *
     * GET /rest/orders/{orderId}
     *
     * @param  array<int,string>|null  $with  Relations to load
     *                                         (e.g. ['addresses','orderItems.variation','documents','comments']).
     */
    public function get(int $orderId, ?array $with = null): Response
    {
        return $this->connector->send(new GetOrderRequest($orderId, $with));
    }

    /**
     * List the configured order statuses of the system (id + names).
     *
     * GET /rest/orders/statuses
     *
     * Order statuses are configurable per system — this returns the actual
     * statuses defined in the instance, not a fixed enum. The response is
     * paginated (`entries`, `isLastPage`, `lastPageNumber`, …).
     */
    public function statuses(?string $lang = null, ?int $page = null, ?int $itemsPerPage = null): Response
    {
        return $this->connector->send(new GetOrderStatusesRequest($lang, $page, $itemsPerPage));
    }

    /**
     * List stock transactions for the items of one order (paginated).
     *
     * GET /rest/orders/items/transactions
     *
     * Only scalar `orderId`, `page`, and `itemsPerPage` parameters are supported.
     * PlentyONE silently ignores multi-order arrays and the tested date-range
     * filters while returning HTTP 200.
     */
    public function itemTransactions(int $orderId, ?int $page = null, ?int $itemsPerPage = null): Response
    {
        return $this->connector->send(new GetOrderItemTransactionsRequest($orderId, $page, $itemsPerPage));
    }

    /**
     * Update an order — send only the fields to change (e.g. ['statusId' => 7]).
     *
     * PUT /rest/orders/{orderId}
     *
     * ⚠️ WRITING call — this mutates the order in PlentyONE.
     *
     * @param  array<string,mixed>  $body  Fields to update.
     */
    public function update(int $orderId, array $body): Response
    {
        return $this->connector->send(new UpdateOrderRequest($orderId, $body));
    }

    /**
     * Convenience: change the status of an order.
     *
     * Pass the exact configured `statusId`. Sub-statuses are decimal (e.g. 8.01),
     * and 8.01 !== 8.1 — use the precise value from `statuses()`.
     *
     * ⚠️ WRITING call — this mutates the order in PlentyONE.
     *
     * @param  int|float|string  $statusId  Target status id (e.g. 7 or 8.01).
     */
    public function setStatus(int $orderId, int|float|string $statusId): Response
    {
        return $this->update($orderId, ['statusId' => $statusId]);
    }
}
