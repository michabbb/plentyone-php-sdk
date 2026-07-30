<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Properties\AttachPropertyToGroupRequest;
use PlentyOne\Requests\Properties\CreatePropertyGroupRelationsRequest;
use PlentyOne\Requests\Properties\CreatePropertyRequest;
use PlentyOne\Requests\Properties\DeletePropertyRequest;
use PlentyOne\Requests\Properties\GetPropertiesRequest;
use PlentyOne\Requests\Properties\GetPropertyGroupRequest;
use PlentyOne\Requests\Properties\GetPropertyGroupsRequest;
use PlentyOne\Requests\Properties\GetPropertyRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class PropertiesResource extends BaseResource
{
    /**
     * Create a property.
     *
     * ⚠️ WRITING call — this creates a permanent property in PlentyONE.
     *
     * @param array<string, mixed> $payload
     */
    public function create(array $payload): Response
    {
        return $this->connector->send(new CreatePropertyRequest($payload));
    }

    public function list(?int $page = null, ?int $itemsPerPage = null, ?string $with = null): Response
    {
        return $this->connector->send(new GetPropertiesRequest($page, $itemsPerPage, $with));
    }

    /**
     * Get a single property. The response always includes the `groups` relation,
     * i.e. every group the property belongs to (a property can be in several groups).
     */
    public function get(int $propertyId): Response
    {
        return $this->connector->send(new GetPropertyRequest($propertyId));
    }

    public function groups(?int $page = null, ?int $itemsPerPage = null, ?string $with = null): Response
    {
        return $this->connector->send(new GetPropertyGroupsRequest($page, $itemsPerPage, $with));
    }

    /**
     * Get a single property group. Pass `with: 'names,properties'` to get every
     * property assigned to the group — including properties whose primary
     * `propertyGroupId` points to a different group.
     */
    public function group(int $groupId, ?string $with = null): Response
    {
        return $this->connector->send(new GetPropertyGroupRequest($groupId, $with));
    }

    /**
     * Attach an existing property to a property group.
     *
     * ⚠️ WRITING call — adds persistent group relations in PlentyONE.
     */
    public function attachToGroup(int $groupId, int $propertyId): Response
    {
        return $this->connector->send(new AttachPropertyToGroupRequest($groupId, $propertyId));
    }

    /**
     * Attach multiple properties to their target groups through the V2 relation API.
     *
     * ⚠️ WRITING call — adds persistent group relations in PlentyONE.
     *
     * @param list<array{propertyId: int, groupId: int}> $relations
     */
    public function attachManyToGroups(array $relations): Response
    {
        return $this->connector->send(new CreatePropertyGroupRelationsRequest($relations));
    }

    /**
     * Delete a property.
     *
     * ⚠️ WRITING call — permanently removes a property from PlentyONE.
     */
    public function delete(int $propertyId): Response
    {
        return $this->connector->send(new DeletePropertyRequest($propertyId));
    }
}
