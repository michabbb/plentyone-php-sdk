<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Properties;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPropertyGroupRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int     $groupId,
        private readonly ?string $with = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/properties/groups/' . $this->groupId;
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'with' => $this->with,
        ], fn ($value) => $value !== null);
    }
}
