<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Variations\GetVariationRequest;
use PlentyOne\Requests\Variations\GetVariationsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class VariationsResource extends BaseResource
{
    public function get(int $itemId, int $variationId): Response
    {
        return $this->connector->send(new GetVariationRequest($itemId, $variationId));
    }

    public function find(string $numberExact): Response
    {
        return $this->connector->send(new GetVariationsRequest(numberExact: $numberExact));
    }

    public function list(array $filters = []): Response
    {
        return $this->connector->send(new GetVariationsRequest(...$filters));
    }
}
