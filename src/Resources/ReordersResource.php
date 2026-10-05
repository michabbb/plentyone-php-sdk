<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Orders\GetOrderRequest;
use PlentyOne\Requests\Orders\SearchOrdersRequest;
use PlentyOne\Requests\Reorders\CreateReorderRequest;
use PlentyOne\Requests\Reorders\GetReorderDeliveryDateRequest;
use PlentyOne\Requests\Reorders\UpdateReorderRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;
use UnexpectedValueException;

class ReordersResource extends BaseResource
{
    private const DefaultRelations = [
        'amounts.vats',
        'dates',
        'properties',
        'relations',
        'orderReferences',
        'addresses',
        'addressRelations',
        'contactSender',
        'warehouseReceiver',
        'location',
        'paymentTerms',
        'payments',
        'tags',
        'comments',
        'documents',
        'shippingPackages',
        'shippingPallets',
        'orderItems.amounts',
        'orderItems.properties',
        'orderItems.orderProperties',
        'orderItems.dates',
        'orderItems.references',
        'orderItems.variation',
        'orderItems.variationBarcodes',
        'orderItems.transactions',
        'orderItems.comments',
        'orderItems.serialNumbers',
    ];

    /**
     * Create a type-12 reorder with header and items via POST /rest/reorders.
     * Choose statusId explicitly; link tags separately with tags()->link().
     *
     * @param array<string,mixed> $payload Fields accepted by PlentyONE, including statusId.
     */
    public function create(array $payload): Response
    {
        return $this->connector->send(new CreateReorderRequest($payload));
    }

    /**
     * Update a reorder via PUT /rest/reorders/{orderId}.
     * Status changes and purchase date (type 16) can initiate the ordering workflow.
     *
     * @param array<string,mixed> $payload Only the fields to change.
     */
    public function update(int $orderId, array $payload): Response
    {
        return $this->connector->send(new UpdateReorderRequest($orderId, $payload));
    }

    /**
     * Get a reorder with its header, items and related data via GET /rest/orders/{orderId}.
     * Returns PlentyONE's response unchanged; rejects orders other than type 12.
     *
     * @param  array<int,string>|null  $with  Replace the default relations; [] loads only API defaults.
     *
     * @see https://developers.plentymarkets.com/en-gb/developers/main/rest-api-guides/purchase-orders.html
     */
    public function get(int $orderId, ?array $with = null): Response
    {
        $response = $this->connector->send(new GetOrderRequest($orderId, $with ?? self::DefaultRelations));

        if (12 !== (int) $response->json('typeId')) {
            throw new UnexpectedValueException("Order {$orderId} is not a reorder (typeId 12).");
        }

        return $response;
    }

    /**
     * Search reorders with the same default relations as get() (paginated).
     * GET /rest/orders/search; orderTypeId is always 12.
     *
     * @param  array<string,mixed>  $filters  Order-search filters, including page, itemsPerPage and with.
     */
    public function search(array $filters = []): Response
    {
        $filters['orderTypeId'] = 12;
        $filters['with'] ??= self::DefaultRelations;

        return $this->connector->send(new SearchOrdersRequest($filters));
    }

    /**
     * Get the calculated delivery date of a reorder.
     *
     * GET /rest/reorders/{orderId}/delivery_date
     */
    public function deliveryDate(int $orderId): Response
    {
        return $this->connector->send(new GetReorderDeliveryDateRequest($orderId));
    }
}
