<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Variations;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetVariationsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly ?string $numberExact = null,
        private readonly ?string $id = null,
        private readonly ?bool $isActive = null,
        private readonly ?int $page = null,
        private readonly ?int $itemsPerPage = null,
        private readonly ?int $categoryId = null,
        private readonly ?bool $isMain = null,
        private readonly ?string $with = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/items/variations';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'numberExact' => $this->numberExact,
            'id' => $this->id,
            'isActive' => $this->isActive,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'categoryId' => $this->categoryId,
            'isMain' => $this->isMain,
            'with' => $this->with,
        ], fn ($value) => $value !== null);
    }
}
