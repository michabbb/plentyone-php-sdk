<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Properties;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeletePropertyRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $propertyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/properties/' . $this->propertyId;
    }
}
