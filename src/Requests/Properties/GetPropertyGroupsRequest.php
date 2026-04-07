<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Properties;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPropertyGroupsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly ?int    $page = null,
        private readonly ?int    $itemsPerPage = null,
        private readonly ?string $with = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/properties/groups';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'page'         => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'with'         => $this->with,
        ], fn ($value) => $value !== null);
    }
}
