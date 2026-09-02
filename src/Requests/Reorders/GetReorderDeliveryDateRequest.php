<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Reorders;

use Saloon\Enums\Method;
use Saloon\Http\Request;

/**
 * Get the calculated delivery date of a reorder.
 *
 * GET /rest/reorders/{orderId}/delivery_date
 */
class GetReorderDeliveryDateRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $orderId,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/reorders/' . $this->orderId . '/delivery_date';
    }
}
