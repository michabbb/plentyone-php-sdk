<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Variations;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetVariationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int     $itemId,
        private readonly int     $variationId,
        private readonly ?string $with = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/items/' . $this->itemId . '/variations/' . $this->variationId;
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'with' => $this->with,
        ], fn ($value) => $value !== null);
    }
}
