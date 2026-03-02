<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Categories\GetCategoriesRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class CategoriesResource extends BaseResource
{
    public function list(array $filters = []): Response
    {
        return $this->connector->send(new GetCategoriesRequest(...$filters));
    }
}
