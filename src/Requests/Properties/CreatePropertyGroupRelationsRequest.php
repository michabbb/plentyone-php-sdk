<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Properties;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreatePropertyGroupRelationsRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /**
     * @param list<array{propertyId: int, groupId: int}> $relations
     */
    public function __construct(
        private readonly array $relations,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/properties/groups/relations';
    }

    /**
     * @return list<array{propertyId: int, groupId: int}>
     */
    protected function defaultBody(): array
    {
        return $this->relations;
    }
}
