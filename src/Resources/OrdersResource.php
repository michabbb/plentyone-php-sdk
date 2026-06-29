<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Orders\GetOrderRequest;
use PlentyOne\Requests\Orders\GetOrderStatusesRequest;
use PlentyOne\Requests\Orders\SearchOrdersRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class OrdersResource extends BaseResource
{
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
}
