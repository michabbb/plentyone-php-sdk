<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Referrers\GetReferrersRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class ReferrersResource extends BaseResource
{
    public function list(): Response
    {
        return $this->connector->send(new GetReferrersRequest());
    }
}
