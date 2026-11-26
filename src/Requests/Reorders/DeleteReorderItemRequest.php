<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Reorders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Delete one item (position) of a reorder.
 *
 * DELETE /rest/orders/{orderId}/items/{orderItemId}
 */
class DeleteReorderItemRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $orderId,
        private readonly int $orderItemId,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/items/{$this->orderItemId}";
    }
}
