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
        private readonly ?string $itemId = null,
        private readonly ?bool   $isActive = null,
        private readonly ?bool   $isMain = null,
        private readonly ?bool   $isBundle = null,
        private readonly ?int    $page = null,
        private readonly ?int    $itemsPerPage = null,
        private readonly ?int    $categoryId = null,
        private readonly ?int    $plentyId = null,
        private readonly ?int    $referrerId = null,
        private readonly ?int    $manufacturerId = null,
        private readonly ?int    $supplierId = null,
        private readonly ?int    $variationTagId = null,
        private readonly ?int    $storeSpecial = null,
        private readonly ?string $with = null,
        private readonly ?string $lang = null,
        private readonly ?string $numberFuzzy = null,
        private readonly ?string $barcode = null,
        private readonly ?string $itemName = null,
        private readonly ?string $itemDescription = null,
        private readonly ?string $flagOne = null,
        private readonly ?string $flagTwo = null,
        private readonly ?string $sku = null,
        private readonly ?string $supplierNumber = null,
        private readonly ?string $supplierNumberFuzzy = null,
        private readonly ?string $updatedBetween = null,
        private readonly ?string $createdBetween = null,
        private readonly ?string $relatedUpdatedBetween = null,
        private readonly ?string $stockWarehouseId = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/items/variations';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'numberExact'           => $this->numberExact,
            'id'                    => $this->id,
            'itemId'                => $this->itemId,
            'isActive'              => $this->isActive,
            'isMain'                => $this->isMain,
            'isBundle'              => $this->isBundle,
            'page'                  => $this->page,
            'itemsPerPage'          => $this->itemsPerPage,
            'categoryId'            => $this->categoryId,
            'plentyId'              => $this->plentyId,
            'referrerId'            => $this->referrerId,
            'manufacturerId'        => $this->manufacturerId,
            'supplierId'            => $this->supplierId,
            'variationTagId'        => $this->variationTagId,
            'storeSpecial'          => $this->storeSpecial,
            'with'                  => $this->with,
            'lang'                  => $this->lang,
            'numberFuzzy'           => $this->numberFuzzy,
            'barcode'               => $this->barcode,
            'itemName'              => $this->itemName,
            'itemDescription'       => $this->itemDescription,
            'flagOne'               => $this->flagOne,
            'flagTwo'               => $this->flagTwo,
            'sku'                   => $this->sku,
            'supplierNumber'        => $this->supplierNumber,
            'supplierNumberFuzzy'   => $this->supplierNumberFuzzy,
            'updatedBetween'        => $this->updatedBetween,
            'createdBetween'        => $this->createdBetween,
            'relatedUpdatedBetween' => $this->relatedUpdatedBetween,
            'stockWarehouseId'      => $this->stockWarehouseId,
        ], fn ($value) => $value !== null);
    }
}
