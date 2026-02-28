<?php

declare(strict_types=1);

namespace PlentyOne\Data;

class Variation
{
    public function __construct(
        public readonly int $id,
        public readonly int $itemId,
        public readonly string $number,
        public readonly ?string $name,
        public readonly bool $isActive,
        public readonly ?float $weightG,
        public readonly ?float $widthMM,
        public readonly ?float $lengthMM,
        public readonly ?float $heightMM,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            itemId: $data['itemId'],
            number: $data['number'] ?? '',
            name: $data['name'] ?? null,
            isActive: $data['isActive'] ?? false,
            weightG: $data['weightG'] ?? null,
            widthMM: $data['widthMM'] ?? null,
            lengthMM: $data['lengthMM'] ?? null,
            heightMM: $data['heightMM'] ?? null,
        );
    }
}
