<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Auth;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class LoginRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly string $username,
        private readonly string $password,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/login';
    }

    protected function defaultBody(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
