<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Images;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class LinkVariationImageRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly int $itemId,
        private readonly int $variationId,
        private readonly int $imageId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/items/' . $this->itemId . '/variations/' . $this->variationId . '/variation_images';
    }

    protected function defaultBody(): array
    {
        return [
            'imageId' => $this->imageId,
        ];
    }
}
