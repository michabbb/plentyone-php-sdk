<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class CancelCatalogStatusRequest extends Request
{
    protected Method $method = Method::POST;

    public function __construct(
        private readonly int $statusId,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/statuses/' . $this->statusId . '/cancel';
    }
}