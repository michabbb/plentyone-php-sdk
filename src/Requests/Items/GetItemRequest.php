<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Items;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetItemRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int     $itemId,
        private readonly ?string $with = null,
        private readonly ?string $lang = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/items/' . $this->itemId;
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'with' => $this->with,
            'lang' => $this->lang,
        ], fn ($value) => $value !== null);
    }
}
