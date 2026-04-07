<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Categories;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCategoryRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int     $id,
        private readonly ?string $with = null,
        private readonly ?string $lang = null,
        private readonly ?string $type = null,
        private readonly ?int    $parentId = null,
        private readonly ?int    $plentyId = null,
        private readonly ?string $name = null,
        private readonly ?string $level = null,
        private readonly ?bool   $linklist = null,
        private readonly ?string $updatedAt = null,
        private readonly ?int    $page = null,
        private readonly ?int    $itemsPerPage = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/categories/' . $this->id;
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'with'         => $this->with,
            'lang'         => $this->lang,
            'type'         => $this->type,
            'parentId'     => $this->parentId,
            'plentyId'     => $this->plentyId,
            'name'         => $this->name,
            'level'        => $this->level,
            'linklist'     => $this->linklist,
            'updatedAt'    => $this->updatedAt,
            'page'         => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
        ], fn ($value) => $value !== null);
    }
}
