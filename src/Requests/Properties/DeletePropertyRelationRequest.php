<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Properties;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeletePropertyRelationRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $relationId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/properties/relations/' . $this->relationId;
    }
}
