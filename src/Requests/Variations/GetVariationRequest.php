<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Variations;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetVariationRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $itemId,
        private readonly int $variationId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/items/' . $this->itemId . '/variations/' . $this->variationId;
    }
}
