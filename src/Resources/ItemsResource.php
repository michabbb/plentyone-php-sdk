<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Items\GetItemDescriptionRequest;
use PlentyOne\Requests\Items\GetItemRequest;
use PlentyOne\Requests\Items\GetItemsRequest;
use PlentyOne\Requests\Items\UpdateItemDescriptionRequest;
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

    public function getDescription(int $itemId, int $variationId, string $lang = 'de'): Response
    {
        return $this->connector->send(new GetItemDescriptionRequest($itemId, $variationId, $lang));
    }

    public function updateDescription(
        int    $itemId,
        int    $variationId,
        string $description,
        string $lang = 'de',
    ): Response {
        return $this->connector->send(
            new UpdateItemDescriptionRequest($itemId, $variationId, $description, $lang),
        );
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
