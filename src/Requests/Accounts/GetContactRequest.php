<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Accounts;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetContactRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int     $contactId,
        private readonly ?string $with = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/accounts/contacts/' . $this->contactId;
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'with' => $this->with,
        ], fn ($value) => $value !== null);
    }
}
