<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Reorders;

use InvalidArgumentException;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class CreateReorderRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param array<string,mixed> $payload Header and items; statusId must be chosen explicitly.
     */
    public function __construct(private readonly array $payload)
    {
        if (!isset($payload['statusId'])) {
            throw new InvalidArgumentException('Choose statusId explicitly when creating a reorder.');
        }
    }

    public function resolveEndpoint(): string
    {
        return '/reorders';
    }

    /** @return array<string,mixed> */
    protected function defaultBody(): array
    {
        return [...$this->payload, 'typeId' => 12];
    }
}
