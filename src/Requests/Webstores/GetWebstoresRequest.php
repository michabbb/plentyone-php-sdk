<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Webstores;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetWebstoresRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/webstores';
    }
}
