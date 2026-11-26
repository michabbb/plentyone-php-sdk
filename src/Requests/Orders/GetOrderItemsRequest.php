<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Orders;

use InvalidArgumentException;
use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List the items (positions) of one order, one page at a time.
 *
 * GET /rest/orders/{orderId}/items
 *
 * Always sorted by item ID ascending. Invalid arguments are rejected before any
 * request is sent; they are never clamped.
 */
class GetOrderItemsRequest extends Request
{
    public const MaxItemsPerPage = 250;

    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $orderId,
        private readonly int $page = 1,
        private readonly int $itemsPerPage = 50,
    ) {
        if ($orderId < 1) {
            throw new InvalidArgumentException('orderId must be a positive integer.');
        }

        if ($page < 1) {
            throw new InvalidArgumentException('page must be a positive integer.');
        }

        if ($itemsPerPage < 1 || $itemsPerPage > self::MaxItemsPerPage) {
            throw new InvalidArgumentException('itemsPerPage must be between 1 and ' . self::MaxItemsPerPage . '.');
        }
    }

    public function resolveEndpoint(): string
    {
        return "/orders/{$this->orderId}/items";
    }

    /** @return array<string,int|string> */
    protected function defaultQuery(): array
    {
        return [
            'page'         => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'sortBy'       => 'id',
            'sortOrder'    => 'asc',
        ];
    }
}
