<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Orders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

/**
 * Update an order.
 *
 * PUT /rest/orders/{orderId}
 *
 * Send only the fields you want to change, e.g. `['statusId' => 7]` to change the
 * order status. Null values are removed from the body automatically.
 *
 * ⚠️ WRITING call — this mutates the order in PlentyONE.
 *
 * @see https://developers.plentymarkets.com/en-gb/developers/main/rest-api-guides/order-data.html
 */
class UpdateOrderRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param  array<string,mixed>  $body  Fields to update (e.g. ['statusId' => 7]).
     */
    public function __construct(
        private readonly int   $orderId,
        private readonly array $body,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/orders/' . $this->orderId;
    }

    /**
     * @return array<string,mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter($this->body, static fn ($value) => $value !== null);
    }
}
