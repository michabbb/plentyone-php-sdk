<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Images;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetVariationImagesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $itemId,
        private readonly int $variationId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/items/' . $this->itemId . '/variations/' . $this->variationId . '/variation_images';
    }
}
