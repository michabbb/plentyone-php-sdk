<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get a single order by ID.
 *
 * GET /rest/orders/{orderId}
 *
 * @see https://developers.plentymarkets.com/en-gb/developers/main/rest-api-guides/order-data.html
 */
class GetOrderRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<int,string>|null  $with  Relations to load (e.g. ['addresses','orderItems.variation','documents','comments']).
     */
    public function __construct(
        private readonly int $orderId,
        private readonly ?array $with = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/orders/' . $this->orderId;
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'with' => $this->with,
        ], fn ($value) => $value !== null);
    }
}
