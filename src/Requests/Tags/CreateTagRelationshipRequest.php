<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Tags;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateTagRelationshipRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly int $tagId,
        private readonly string $tagType,
        private readonly int $relationshipValue,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/tags/relationships';
    }

    protected function defaultBody(): array
    {
        return [
            'tagId' => $this->tagId,
            'tagType' => $this->tagType,
            'relationshipValue' => $this->relationshipValue,
        ];
    }
}
