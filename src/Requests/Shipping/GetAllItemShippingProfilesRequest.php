<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Shipping;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetAllItemShippingProfilesRequest extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/items/item_shipping_profiles';
    }
}
