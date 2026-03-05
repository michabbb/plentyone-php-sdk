<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Webstores\GetWebstoresRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class WebstoresResource extends BaseResource
{
    public function list(): Response
    {
        return $this->connector->send(new GetWebstoresRequest());
    }
}
