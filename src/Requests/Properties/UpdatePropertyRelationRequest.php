<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Properties;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdatePropertyRelationRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    public function __construct(
        private readonly int    $relationId,
        private readonly string $fileUrl,
        private readonly string $lang = 'de',
    ) {}

    public function resolveEndpoint(): string
    {
        return '/properties/relations/' . $this->relationId;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return [
            'relationValues' => [
                [
                    'value' => $this->fileUrl,
                    'lang'  => $this->lang,
                ],
            ],
        ];
    }
}
