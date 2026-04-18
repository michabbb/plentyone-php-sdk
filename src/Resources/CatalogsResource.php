<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Catalogs\ActivateCatalogRequest;
use PlentyOne\Requests\Catalogs\CopyCatalogFormatRequest;
use PlentyOne\Requests\Catalogs\CopyCatalogRequest;
use PlentyOne\Requests\Catalogs\CreateCatalogRequest;
use PlentyOne\Requests\Catalogs\CheckCatalogConnectionRequest;
use PlentyOne\Requests\Catalogs\DeleteCatalogRequest;
use PlentyOne\Requests\Catalogs\UpdateCatalogRequest;
use PlentyOne\Requests\Catalogs\UpdateCatalogContentRequest;
use PlentyOne\Requests\Catalogs\ExportCatalogRequest;
use PlentyOne\Requests\Catalogs\GetCatalogArchiveRequest;
use PlentyOne\Requests\Catalogs\ImportCatalogRequest;
use PlentyOne\Requests\Catalogs\MigrateCatalogRequest;
use PlentyOne\Requests\Catalogs\RestoreCatalogRequest;
use PlentyOne\Requests\Catalogs\GetCatalogContentRequest;
use PlentyOne\Requests\Catalogs\GetCatalogPreviewRequest;
use PlentyOne\Requests\Catalogs\GetCatalogPreviewVdiRequest;
use PlentyOne\Requests\Catalogs\GetCatalogPrivateUrlRequest;
use PlentyOne\Requests\Catalogs\GetCatalogPublicUrlRequest;
use PlentyOne\Requests\Catalogs\GetCatalogRequest;
use PlentyOne\Requests\Catalogs\GetCatalogsRequest;
use PlentyOne\Requests\Catalogs\GetCatalogScheduleDaysRequest;
use PlentyOne\Requests\Catalogs\GetCatalogTokenRequest;
use PlentyOne\Requests\Catalogs\GetCatalogTemplatesRequest;
use PlentyOne\Requests\Catalogs\GetCatalogVersionRequest;
use PlentyOne\Requests\Catalogs\GetCatalogVersionsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class CatalogsResource extends BaseResource
{
    public function list(): Response
    {
        return $this->connector->send(new GetCatalogsRequest());
    }

    public function create(array $body = []): Response
    {
        return $this->connector->send(new CreateCatalogRequest($body));
    }

    public function copy(array $body = []): Response
    {
        return $this->connector->send(new CopyCatalogRequest($body));
    }

    public function copyFormat(string $catalogId, array $body = []): Response
    {
        return $this->connector->send(new CopyCatalogFormatRequest($catalogId, $body));
    }

    public function import(array $body = []): Response
    {
        return $this->connector->send(new ImportCatalogRequest($body));
    }

    public function migrate(): Response
    {
        return $this->connector->send(new MigrateCatalogRequest());
    }

    public function activate(string $id, bool $active = true): Response
    {
        return $this->connector->send(new ActivateCatalogRequest($id, $active));
    }

    public function archive(): Response
    {
        return $this->connector->send(new GetCatalogArchiveRequest());
    }

    public function restore(string $id): Response
    {
        return $this->connector->send(new RestoreCatalogRequest($id));
    }

    public function get(string $id): Response
    {
        return $this->connector->send(new GetCatalogRequest($id));
    }

    public function delete(string $id): Response
    {
        return $this->connector->send(new DeleteCatalogRequest($id));
    }

    public function update(string $id, array $body = []): Response
    {
        return $this->connector->send(new UpdateCatalogRequest($id, $body));
    }

    public function preview(string $id): Response
    {
        return $this->connector->send(new GetCatalogPreviewRequest($id));
    }

    public function previewVdi(string $id, int $variationId): Response
    {
        return $this->connector->send(new GetCatalogPreviewVdiRequest($id, $variationId));
    }

    public function content(string $id): Response
    {
        return $this->connector->send(new GetCatalogContentRequest($id));
    }

    public function updateContent(string $id, array $body = []): Response
    {
        return $this->connector->send(new UpdateCatalogContentRequest($id, $body));
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

    public function scheduleDays(): Response
    {
        return $this->connector->send(new GetCatalogScheduleDaysRequest());
    }

    public function token(): Response
    {
        return $this->connector->send(new GetCatalogTokenRequest());
    }

    public function checkConnection(string $protocol, array $body = []): Response
    {
        return $this->connector->send(new CheckCatalogConnectionRequest($protocol, $body));
    }
}
