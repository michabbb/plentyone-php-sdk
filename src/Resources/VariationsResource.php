<?php

declare(strict_types=1);

namespace PlentyOne\Resources;

use PlentyOne\Requests\Properties\CreatePropertyRelationRequest;
use PlentyOne\Requests\Properties\DeletePropertyRelationRequest;
use PlentyOne\Requests\Properties\UpdatePropertyRelationRequest;
use PlentyOne\Requests\Variations\GetVariationRequest;
use PlentyOne\Requests\Variations\GetVariationsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class VariationsResource extends BaseResource
{
    public function get(int $itemId, int $variationId, ?string $with = null): Response
    {
        return $this->connector->send(new GetVariationRequest($itemId, $variationId, $with));
    }

    /**
     * Dokumente (File-Properties) einer Variation laden.
     * Gibt nur Properties vom Typ "file" zurück.
     */
    public function documents(int $itemId, int $variationId): array
    {
        $response = $this->get($itemId, $variationId, 'properties,variationProperties');
        $data = $response->json();
        $properties = $data['properties'] ?? [];

        return array_values(array_filter($properties, function (array $prop) {
            return ($prop['propertyRelation']['cast'] ?? '') === 'file';
        }));
    }

    /**
     * Dokument (File-Property) hinzufügen oder aktualisieren.
     * Legt die Property automatisch an, wenn sie noch nicht verknüpft ist.
     */
    public function addDocument(int $itemId, int $variationId, int $propertyId, string $fileUrl, string $lang = 'de'): Response
    {
        $existing = $this->documents($itemId, $variationId);

        foreach ($existing as $doc) {
            if ($doc['propertyId'] === $propertyId) {
                return $this->connector->send(
                    new UpdatePropertyRelationRequest($doc['id'], $fileUrl, $lang)
                );
            }
        }

        return $this->connector->send(
            new CreatePropertyRelationRequest($propertyId, $variationId, $fileUrl, $lang)
        );
    }

    /**
     * Dokument (File-Property) von einer Variation entfernen.
     */
    public function removeDocument(int $itemId, int $variationId, int $propertyId): Response
    {
        $existing = $this->documents($itemId, $variationId);

        foreach ($existing as $doc) {
            if ($doc['propertyId'] === $propertyId) {
                return $this->connector->send(
                    new DeletePropertyRelationRequest($doc['id'])
                );
            }
        }

        throw new \RuntimeException("Property $propertyId nicht an Variation $variationId gefunden.");
    }

    public function find(string $numberExact): Response
    {
        return $this->connector->send(new GetVariationsRequest(numberExact: $numberExact));
    }

    public function list(array $filters = []): Response
    {
        return $this->connector->send(new GetVariationsRequest(...$filters));
    }
}
