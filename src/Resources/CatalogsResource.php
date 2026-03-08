<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Catalogs\ExportCatalogRequest;
use PlentyOne\Requests\Catalogs\GetCatalogPreviewRequest;
use PlentyOne\Requests\Catalogs\GetCatalogPrivateUrlRequest;
use PlentyOne\Requests\Catalogs\GetCatalogPublicUrlRequest;
use PlentyOne\Requests\Catalogs\GetCatalogRequest;
use PlentyOne\Requests\Catalogs\GetCatalogTemplatesRequest;
use PlentyOne\Requests\Catalogs\GetCatalogVersionRequest;
use PlentyOne\Requests\Catalogs\GetCatalogVersionsRequest;
use PlentyOne\Requests\Catalogs\GetCatalogsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class CatalogsResource extends BaseResource
{
    public function list(): Response
    {
        return $this->connector->send(new GetCatalogsRequest());
    }

    public function get(string $id): Response
    {
        return $this->connector->send(new GetCatalogRequest($id));
    }

    public function preview(string $id): Response
    {
        return $this->connector->send(new GetCatalogPreviewRequest($id));
    }

    public function publicUrl(string $id): Response
    {
        return $this->connector->send(new GetCatalogPublicUrlRequest($id));
    }

    public function privateUrl(string $id): Response
    {
        return $this->connector->send(new GetCatalogPrivateUrlRequest($id));
    }

    public function export(string $id): Response
    {
        return $this->connector->send(new ExportCatalogRequest($id));
    }

    public function versions(string $id): Response
    {
        return $this->connector->send(new GetCatalogVersionsRequest($id));
    }

    public function version(string $id, string $versionId): Response
    {
        return $this->connector->send(new GetCatalogVersionRequest($id, $versionId));
    }

    public function templates(): Response
    {
        return $this->connector->send(new GetCatalogTemplatesRequest());
    }
}
