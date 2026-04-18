<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Catalogs;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetCatalogStatusHistoryFileRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        private readonly int $id,
        private readonly string $filename,
    ) {}

    public function resolveEndpoint(): string
    {
        return '/catalogs/statuses/' . $this->id . '/histories/' . rawurlencode($this->filename);
    }
}