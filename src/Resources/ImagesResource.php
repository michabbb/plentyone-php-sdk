<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Images\DeleteItemImageRequest;
use PlentyOne\Requests\Images\GetItemImagesRequest;
use PlentyOne\Requests\Images\GetVariationImagesRequest;
use PlentyOne\Requests\Images\LinkVariationImageRequest;
use PlentyOne\Requests\Images\UnlinkVariationImageRequest;
use PlentyOne\Requests\Images\UpdateItemImageRequest;
use PlentyOne\Requests\Images\UploadItemImageRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class ImagesResource extends BaseResource
{
    public function forItem(int $itemId): Response
    {
        return $this->connector->send(new GetItemImagesRequest($itemId));
    }

    public function forVariation(int $itemId, int $variationId): Response
    {
        return $this->connector->send(new GetVariationImagesRequest($itemId, $variationId));
    }

    public function upload(
        int     $itemId,
        ?string $uploadUrl = null,
        ?string $uploadImageData = null,
        ?string $uploadFileName = null,
        ?string $fileType = null,
        int     $position = 0,
        ?string $name = null,
        ?string $alternate = null,
    ): Response {
        return $this->connector->send(new UploadItemImageRequest(
            $itemId,
            $uploadUrl,
            $uploadImageData,
            $uploadFileName,
            $fileType,
            $position,
            $name,
            $alternate,
        ));
    }

    public function updatePosition(int $itemId, int $imageId, int $position): Response
    {
        return $this->connector->send(new UpdateItemImageRequest($itemId, $imageId, $position));
    }

    public function delete(int $itemId, int $imageId): Response
    {
        return $this->connector->send(new DeleteItemImageRequest($itemId, $imageId));
    }

    public function linkToVariation(int $itemId, int $variationId, int $imageId): Response
    {
        return $this->connector->send(new LinkVariationImageRequest($itemId, $variationId, $imageId));
    }

    public function unlinkFromVariation(int $itemId, int $variationId, int $imageId): Response
    {
        return $this->connector->send(new UnlinkVariationImageRequest($itemId, $variationId, $imageId));
    }
}
