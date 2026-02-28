<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Images;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateItemImageRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        private readonly int $itemId,
        private readonly int $imageId,
        private readonly ?int $position = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/items/' . $this->itemId . '/images/' . $this->imageId;
    }

    protected function defaultBody(): array
    {
        return array_filter([
            'position' => $this->position,
        ], fn ($value) => $value !== null);
    }
}
