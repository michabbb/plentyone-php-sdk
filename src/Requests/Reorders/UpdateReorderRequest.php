<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Reorders;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateReorderRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::PUT;

    /** @param array<string,mixed> $payload Fields to update, including nested item data. */
    public function __construct(
        private readonly int   $orderId,
        private readonly array $payload,
    ) {}

    public function resolveEndpoint(): string
    {
        return "/reorders/{$this->orderId}";
    }

    /** @return array<string,mixed> */
    protected function defaultBody(): array
    {
        return $this->payload;
    }
}
