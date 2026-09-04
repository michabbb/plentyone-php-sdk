<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Items;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateItemDescriptionRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        private readonly int    $itemId,
        private readonly int    $variationId,
        private readonly string $description,
        private readonly string $lang,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/items/{$this->itemId}/variations/{$this->variationId}/descriptions/{$this->lang}";
    }

    /**
     * @return array{description: string}
     */
    protected function defaultBody(): array
    {
        return [
            'description' => $this->description,
        ];
    }
}
