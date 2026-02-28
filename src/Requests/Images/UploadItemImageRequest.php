<?php

declare(strict_types=1);

namespace PlentyOne\Requests\Images;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UploadItemImageRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        private readonly int $itemId,
        private readonly ?string $uploadUrl = null,
        private readonly ?string $uploadImageData = null,
        private readonly ?string $uploadFileName = null,
        private readonly ?string $fileType = null,
        private readonly int $position = 0,
        private readonly ?string $name = null,
        private readonly ?string $alternate = null,
        private readonly string $lang = 'de',
    ) {}

    public function resolveEndpoint(): string
    {
        return '/items/' . $this->itemId . '/images/upload';
    }

    protected function defaultBody(): array
    {
        $body = [
            'position' => $this->position,
        ];

        if ($this->uploadUrl !== null) {
            $body['uploadUrl'] = $this->uploadUrl;
        }

        if ($this->uploadImageData !== null) {
            $body['uploadImageData'] = $this->uploadImageData;
            $body['uploadFileName'] = $this->uploadFileName ?? 'image.jpg';
            if ($this->fileType !== null) {
                $body['fileType'] = $this->fileType;
            }
        }

        if ($this->name !== null || $this->alternate !== null) {
            $body['names'] = [
                [
                    'lang' => $this->lang,
                    'name' => $this->name ?? '',
                    'alternate' => $this->alternate ?? '',
                ],
            ];
        }

        return $body;
    }
}
