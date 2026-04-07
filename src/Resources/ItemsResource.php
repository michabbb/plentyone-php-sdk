<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Items\GetItemRequest;
use PlentyOne\Requests\Items\GetItemsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class ItemsResource extends BaseResource
{
    /**
     * Get a single item by ID.
     */
    public function get(int $itemId, ?string $with = null, ?string $lang = null): Response
    {
        return $this->connector->send(new GetItemRequest($itemId, $with, $lang));
    }

    /**
     * Search items with filters.
     */
    public function list(
        ?string $id = null,
        ?string $name = null,
        ?string $manufacturerId = null,
        ?int    $flagOne = null,
        ?int    $flagTwo = null,
        ?int    $page = null,
        ?int    $itemsPerPage = null,
        ?string $with = null,
        ?string $lang = null,
        ?string $updatedBetween = null,
        ?string $variationUpdatedBetween = null,
        ?string $variationRelatedUpdatedBetween = null,
        ?string $or = null,
    ): Response {
        return $this->connector->send(new GetItemsRequest(
            $id,
            $name,
            $manufacturerId,
            $flagOne,
            $flagTwo,
            $page,
            $itemsPerPage,
            $with,
            $lang,
            $updatedBetween,
            $variationUpdatedBetween,
            $variationRelatedUpdatedBetween,
            $or,
        ));
    }
}
