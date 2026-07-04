<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Orders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * List the configured order statuses of the system.
 *
 * GET /rest/orders/statuses
 *
 * Order statuses are not a fixed enum — they are configured per PlentyONE system.
 * This endpoint returns the actual statuses (id + names) defined in the instance.
 *
 * @see https://developers.plentymarkets.com/en-gb/developers/main/rest-api-guides/order-data.html
 */
class GetOrderStatusesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly ?string $lang = null,
        private readonly ?int    $page = null,
        private readonly ?int    $itemsPerPage = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/orders/statuses';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'lang'         => $this->lang,
            'page'         => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
        ], fn ($value) => $value !== null);
    }
}
