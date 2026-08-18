<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List orders — the only order endpoint that supports date *ranges*.
 *
 * GET /rest/orders
 *
 * Use this instead of `SearchOrdersRequest` whenever orders have to be selected by
 * a time span: `/orders/search` filters `createdAt` / `updatedAt` for **equality
 * only**, and its `createdAtFrom` / `createdAtTo` variants either fail with HTTP 500
 * or are silently ignored. `/orders` is the counterpart: it understands the range
 * filters but ignores most of the search filters (see below).
 *
 * A plain array is used (instead of enumerated named parameters) because the filter
 * list is long and partly dynamic — the same convention as `SearchOrdersRequest`.
 *
 * Verified filters (measured against a live system on 2026-08-19):
 *
 * - `createdAtFrom` — W3C date/time **including** timezone, e.g. `2026-08-17T00:00:00+02:00`
 * - `createdAtTo`   — same format
 * - `updatedAtFrom` — same format; use this for incremental syncs so that later
 *                     changes to old orders (cancellations, returns) are picked up
 * - `updatedAtTo`   — same format
 * - `page`          — int, 1-based
 * - `itemsPerPage`  — int, default 50, **maximum 250** (higher → HTTP 422
 *                     "The number of items per page exceeds the maximum of 250.")
 * - `with`          — array of relations, e.g. `['orderItems.amounts']`
 *
 * A date without a timezone is rejected with HTTP 422
 * ("Error parsing date string. String must be in W3C format.").
 *
 * Both range bounds are **inclusive**: an order created at exactly `T` is returned by
 * `createdAtFrom=T&createdAtTo=T`. Splitting a large backfill into consecutive slices
 * may therefore return boundary orders twice, but never drops one.
 *
 * Pagination cap: `page * itemsPerPage` must not exceed **60,000**, otherwise the API
 * answers HTTP 422 ("The pagination depth (page * itemsPerPage) exceeds the maximum of
 * 60000"). The same cap applies to `/orders/search`. Callers that need more than 60,000
 * orders have to split the query into smaller time slices — this SDK deliberately does
 * not enforce or work around the cap, it stays a thin wrapper.
 *
 * Not supported here (accepted with HTTP 200 but **silently ignored** — verified against
 * a control measurement): `orderTypeId` and `withDeleted`. Both work on `/orders/search`,
 * so filtering by order type or including deleted orders has to happen either there or
 * client-side on `typeId`.
 *
 * Note on data types: `referrerId` and `statusId` are decimals with two fraction digits
 * (e.g. `4.01`, `8.03`). They are passed through untouched — no conversion happens here.
 *
 * @see https://developers.plentymarkets.com/en-gb/developers/main/rest-api-guides/order-data.html
 */
class ListOrdersRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<string,mixed>  $filters  Query filters. Null values are removed automatically.
     */
    public function __construct(
        private readonly array $filters = [],
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/orders';
    }

    protected function defaultQuery(): array
    {
        return array_filter($this->filters, fn ($value) => $value !== null);
    }
}
