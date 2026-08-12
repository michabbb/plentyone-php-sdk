<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Tags\CreateTagRelationshipRequest;
use PlentyOne\Requests\Tags\DeleteTagRelationshipRequest;
use PlentyOne\Requests\Tags\GetTagRelationshipsRequest;
use PlentyOne\Requests\Tags\GetTagsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class TagsResource extends BaseResource
{
    public function list(?int $page = null, ?int $itemsPerPage = null, ?string $with = null): Response
    {
        return $this->connector->send(new GetTagsRequest($page, $itemsPerPage, $with));
    }

    public function relationships(
        int    $tagId,
        string $type,
        ?int   $page = null,
        ?int   $itemsPerPage = null,
    ): Response {
        return $this->connector->send(new GetTagRelationshipsRequest($tagId, $type, $page, $itemsPerPage));
    }

    public function link(int $tagId, string $tagType, int $relationshipValue): Response
    {
        return $this->connector->send(new CreateTagRelationshipRequest($tagId, $tagType, $relationshipValue));
    }

    public function unlink(int $tagId, string $tagType, int $relationshipValue): Response
    {
        return $this->connector->send(new DeleteTagRelationshipRequest($tagId, $tagType, $relationshipValue));
    }
}
