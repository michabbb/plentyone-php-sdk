<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Search orders.
 *
 * GET /rest/orders/search
 *
 * Accepts the full set of order-search filters as an associative array.
 * A plain array is used (instead of enumerated named parameters) because the
 * endpoint supports dynamic filter names whose suffix is a runtime ID, e.g.
 * `orderProperty_{typeId}`, `orderDate_{typeId}`, `documentNumber_{documentType}`,
 * `addressRelation_{typeId}`, `relationReference_{referenceType}_{relationType}`.
 *
 * Static filters include: orderId, plentyId, orderTypeId, statusId, referrerId,
 * ownerId, locationId, createdAt, updatedAt, lockStatus, contactData,
 * orderAddressData, itemId, itemVariationId, variationNumber, orderItemName,
 * documentNumber, hasValidInvoice, packageNumber, contactClassId,
 * itemManufacturerId, orderItemWarehouseId, tag, excludeMainOrders,
 * shippingServiceProviderId, shippingStatus, sortBy, sortOrder, page,
 * itemsPerPage, with (array), lazyLoaded, withDeleted.
 *
 * @see https://developers.plentymarkets.com/en-gb/developers/main/rest-api-guides/order-data.html
 */
class SearchOrdersRequest extends Request
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
        return '/orders/search';
    }

    protected function defaultQuery(): array
    {
        return array_filter($this->filters, fn ($value) => $value !== null);
    }
}
