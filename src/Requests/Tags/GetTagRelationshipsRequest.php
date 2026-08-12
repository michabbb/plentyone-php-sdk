<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetTagRelationshipsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int    $tagId,
        private readonly string $type,
        private readonly ?int   $page = null,
        private readonly ?int   $itemsPerPage = null,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/v2/tags/relationships';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'tagId'        => $this->tagId,
            'type'         => $this->type,
            'page'         => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
        ], fn ($value) => $value !== null);
    }
}
