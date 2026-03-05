<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Properties\GetPropertiesRequest;
use PlentyOne\Requests\Properties\GetPropertyGroupsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class PropertiesResource extends BaseResource
{
    public function list(?int $page = null, ?int $itemsPerPage = null, ?string $with = null): Response
    {
        return $this->connector->send(new GetPropertiesRequest($page, $itemsPerPage, $with));
    }

    public function groups(?int $page = null, ?int $itemsPerPage = null, ?string $with = null): Response
    {
        return $this->connector->send(new GetPropertyGroupsRequest($page, $itemsPerPage, $with));
    }
}
