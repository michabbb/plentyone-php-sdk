<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Shipping;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetShippingPresetsRequest extends Request
{
    protected Method $method = Method::GET;

    /**
     * @param  array<int, string>|null  $columns
     */
    public function __construct(
        private readonly ?string $parcelServiceName = null,
        private readonly ?string $shippingServiceProvider = null,
        private readonly ?string $name = null,
        private readonly ?string $shippingGroup = null,
        private readonly ?string $with = null,
        private readonly ?string $updatedAtBefore = null,
        private readonly ?string $updatedAtAfter = null,
        private readonly ?array  $columns = null,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/orders/shipping/presets';
    }

    protected function defaultQuery(): array
    {
        return array_filter([
            'parcelServiceName'       => $this->parcelServiceName,
            'shippingServiceProvider' => $this->shippingServiceProvider,
            'name'                    => $this->name,
            'shippingGroup'           => $this->shippingGroup,
            'with'                    => $this->with,
            'updatedAtBefore'         => $this->updatedAtBefore,
            'updatedAtAfter'          => $this->updatedAtAfter,
            'columns'                 => $this->columns ? implode(',', $this->columns) : null,
        ], fn (?string $value): bool => $value !== null);
    }
}
