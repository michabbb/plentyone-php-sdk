<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Properties;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreatePropertyRelationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly int    $propertyId,
        private readonly int    $relationTargetId,
        private readonly string $fileUrl,
        private readonly string $lang = 'de',
    ) {}

    public function resolveEndpoint(): string
    {
        return '/properties/relations';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'propertyId'             => $this->propertyId,
            'relationTypeIdentifier' => 'item',
            'relationTargetId'       => $this->relationTargetId,
            'relationValues'         => [
                [
                    'value' => $this->fileUrl,
                    'lang'  => $this->lang,
                ],
            ],
        ];
    }
}
