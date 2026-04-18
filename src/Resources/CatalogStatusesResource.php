<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Catalogs\CancelCatalogStatusRequest;
use PlentyOne\Requests\Catalogs\GetCatalogStatusDataRequest;
use PlentyOne\Requests\Catalogs\GetCatalogStatusHistoriesRequest;
use PlentyOne\Requests\Catalogs\GetCatalogStatusHistoryFileRequest;
use PlentyOne\Requests\Catalogs\GetCatalogStatusLogsRequest;
use PlentyOne\Requests\Catalogs\GetCatalogStatusRequest;
use PlentyOne\Requests\Catalogs\GetCatalogStatusesRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class CatalogStatusesResource extends BaseResource
{
    public function list(?string $catalogId = null, ?int $page = null, ?int $itemsPerPage = null): Response
    {
        return $this->connector->send(new GetCatalogStatusesRequest($catalogId, $page, $itemsPerPage));
    }

    public function get(int $id): Response
    {
        return $this->connector->send(new GetCatalogStatusRequest($id));
    }

    public function data(int $id): Response
    {
        return $this->connector->send(new GetCatalogStatusDataRequest($id));
    }

    public function logs(int $id): Response
    {
        return $this->connector->send(new GetCatalogStatusLogsRequest($id));
    }

    public function histories(int $id): Response
    {
        return $this->connector->send(new GetCatalogStatusHistoriesRequest($id));
    }

    public function historyFile(int $id, string $filename): Response
    {
        return $this->connector->send(new GetCatalogStatusHistoryFileRequest($id, $filename));
    }

    public function cancel(int $statusId): Response
    {
        return $this->connector->send(new CancelCatalogStatusRequest($statusId));
    }
}