<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Referrers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetReferrersRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/orders/referrers';
    }
}
