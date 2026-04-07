<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Tags;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteTagRelationshipRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly int    $tagId,
        private readonly string $tagType,
        private readonly int    $relationshipValue,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/tags/relationships';
    }

    protected function defaultQuery(): array
    {
        return [
            'tagId'             => $this->tagId,
            'tagType'           => $this->tagType,
            'relationshipValue' => $this->relationshipValue,
        ];
    }
}
