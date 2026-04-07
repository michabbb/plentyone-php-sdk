<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Manufacturers;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetManufacturerRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $id,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/items/manufacturers/' . $this->id;
    }
}
