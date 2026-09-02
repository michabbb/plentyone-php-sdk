<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List stock transactions for the items of one order.
 *
 * GET /rest/orders/items/transactions
 *
 * The endpoint is paginated. Only the verified scalar filters are exposed: one
 * `orderId`, `page`, and `itemsPerPage`. PlentyONE silently ignores an array of
 * order IDs and the tested `createdAt...` / `updatedAt...` range filters while
 * still returning HTTP 200, so callers must not rely on those filter shapes.
 */
class GetOrderItemTransactionsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int  $orderId,
        private readonly ?int $page = null,
        private readonly ?int $itemsPerPage = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/orders/items/transactions';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'orderId'      => $this->orderId,
            'page'         => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
        ], fn ($value) => $value !== null);
    }
}
