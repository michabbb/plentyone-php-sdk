<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Properties;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreatePropertyRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param array<string, mixed> $payload Property fields accepted by PlentyONE.
     */
    public function __construct(
        private readonly array $payload,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/properties';
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaultBody(): array
    {
        return array_filter($this->payload, static fn ($value) => $value !== null);
    }
}
