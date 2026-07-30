<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Properties;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class AttachPropertyToGroupRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        private readonly int $groupId,
        private readonly int $propertyId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/properties/groups/' . $this->groupId . '/properties/' . $this->propertyId;
    }
}
