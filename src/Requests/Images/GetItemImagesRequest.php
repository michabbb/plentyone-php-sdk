<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Images;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetItemImagesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $itemId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/items/' . $this->itemId . '/images';
    }
}
