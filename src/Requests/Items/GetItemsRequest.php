<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Items;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetItemsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly ?string $id = null,
        private readonly ?string $name = null,
        private readonly ?string $manufacturerId = null,
        private readonly ?int    $flagOne = null,
        private readonly ?int    $flagTwo = null,
        private readonly ?int    $page = null,
        private readonly ?int    $itemsPerPage = null,
        private readonly ?string $with = null,
        private readonly ?string $lang = null,
        private readonly ?string $updatedBetween = null,
        private readonly ?string $variationUpdatedBetween = null,
        private readonly ?string $variationRelatedUpdatedBetween = null,
        private readonly ?string $or = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/items';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'id'                             => $this->id,
            'name'                           => $this->name,
            'manufacturerId'                 => $this->manufacturerId,
            'flagOne'                        => $this->flagOne,
            'flagTwo'                        => $this->flagTwo,
            'page'                           => $this->page,
            'itemsPerPage'                   => $this->itemsPerPage,
            'with'                           => $this->with,
            'lang'                           => $this->lang,
            'updatedBetween'                 => $this->updatedBetween,
            'variationUpdatedBetween'        => $this->variationUpdatedBetween,
            'variationRelatedUpdatedBetween' => $this->variationRelatedUpdatedBetween,
            'or'                             => $this->or,
        ], fn ($value) => $value !== null);
    }
}
