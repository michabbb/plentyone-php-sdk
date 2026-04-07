<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Shipping\GetAllItemShippingProfilesRequest;
use PlentyOne\Requests\Shipping\GetItemShippingProfilesRequest;
use PlentyOne\Requests\Shipping\GetShippingPresetsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class ShippingResource extends BaseResource
{
    /**
     * List all shipping profiles (presets).
     *
     * @param  array<int, string>|null  $columns
     */
    public function presets(
        ?string $parcelServiceName = null,
        ?string $shippingServiceProvider = null,
        ?string $name = null,
        ?string $shippingGroup = null,
        ?string $with = null,
        ?string $updatedAtBefore = null,
        ?string $updatedAtAfter = null,
        ?array  $columns = null,
    ): Response {
        return $this->connector->send(new GetShippingPresetsRequest(
            $parcelServiceName,
            $shippingServiceProvider,
            $name,
            $shippingGroup,
            $with,
            $updatedAtBefore,
            $updatedAtAfter,
            $columns,
        ));
    }

    /**
     * List shipping profiles linked to a specific item.
     */
    public function forItem(int $itemId): Response
    {
        return $this->connector->send(new GetItemShippingProfilesRequest($itemId));
    }

    /**
     * List all shipping profiles of all items (paginated).
     */
    public function allItemProfiles(): Response
    {
        return $this->connector->send(new GetAllItemShippingProfilesRequest());
    }
}
