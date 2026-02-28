<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Images;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class UnlinkVariationImageRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int $itemId,
        private readonly int $variationId,
        private readonly int $imageId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/items/' . $this->itemId . '/variations/' . $this->variationId . '/variation_images/' . $this->imageId;
    }
}
