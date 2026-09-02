<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Reorders\GetReorderDeliveryDateRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class ReordersResource extends BaseResource
{
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
