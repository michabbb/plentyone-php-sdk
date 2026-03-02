<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Categories;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCategoriesRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly ?string $type = null,
        private readonly ?string $with = null,
        private readonly ?int $page = null,
        private readonly ?int $itemsPerPage = null,
        private readonly ?int $parentId = null,
        private readonly ?string $lang = null,
        private readonly ?int $plentyId = null,
        private readonly ?string $name = null,
        private readonly ?string $level = null,
        private readonly ?bool $linklist = null,
        private readonly ?string $updatedAt = null,
        private readonly ?int $tagId = null,
        private readonly ?string $metaKeywords = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/categories';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'type' => $this->type,
            'with' => $this->with,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'parentId' => $this->parentId,
            'lang' => $this->lang,
            'plentyId' => $this->plentyId,
            'name' => $this->name,
            'level' => $this->level,
            'linklist' => $this->linklist,
            'updatedAt' => $this->updatedAt,
            'tagId' => $this->tagId,
            'metaKeywords' => $this->metaKeywords,
        ], fn ($value) => $value !== null);
    }
}
