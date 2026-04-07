<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Categories\GetCategoriesRequest;
use PlentyOne\Requests\Categories\GetCategoryRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class CategoriesResource extends BaseResource
{
    /**
     * @param  array<string, mixed>  $filters
     */
    public function list(array $filters = []): Response
    {
        return $this->connector->send(new GetCategoriesRequest(...$filters));
    }

    /**
     * Get a single category by ID.
     *
     * Note: PlentyONE returns a paginated response with one entry — even for a
     * single-ID lookup. Use $response->json('entries.0') to grab the category.
     *
     * @param  int          $id    Category ID
     * @param  string|null  $with  Comma-separated relations: details, clients, elmarCategories
     * @param  string|null  $lang  ISO language(s), comma-separated (e.g. "de" or "de,en")
     *
     * @see https://developers.plentymarkets.com/en-gb/plentymarkets-rest-api/index.html#/Category/get_rest_categories__id_
     */
    public function get(
        int     $id,
        ?string $with = null,
        ?string $lang = null,
        ?string $type = null,
        ?int    $parentId = null,
        ?int    $plentyId = null,
        ?string $name = null,
        ?string $level = null,
        ?bool   $linklist = null,
        ?string $updatedAt = null,
        ?int    $page = null,
        ?int    $itemsPerPage = null,
    ): Response {
        return $this->connector->send(new GetCategoryRequest(
            $id,
            $with,
            $lang,
            $type,
            $parentId,
            $plentyId,
            $name,
            $level,
            $linklist,
            $updatedAt,
            $page,
            $itemsPerPage,
        ));
    }
}
