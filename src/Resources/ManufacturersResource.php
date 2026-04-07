<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Manufacturers\GetManufacturerRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class ManufacturersResource extends BaseResource
{
    /**
     * Get a single manufacturer by ID.
     *
     * @see https://developers.plentymarkets.com/en-gb/plentymarkets-rest-api/index.html#/Item/get_rest_items_manufacturers__id_
     */
    public function get(int $id): Response
    {
        return $this->connector->send(new GetManufacturerRequest($id));
    }
}
