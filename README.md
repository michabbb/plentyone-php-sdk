# PlentyOne PHP SDK

A lightweight PHP SDK for the [PlentyONE REST API](https://developers.plentymarkets.com), built on [Saloon PHP v3](https://docs.saloon.dev).

## Requirements

- PHP 8.2+
- Composer

## Installation

```bash
composer require macropage/plentyone-php-sdk
```

## Quick Start

```php
use PlentyOne\PlentyOneConnector;

$connector = new PlentyOneConnector('https://your-instance.plentymarkets.com/rest');
$connector->login('username', 'password');

// Find a variation by SKU
$response = $connector->variations()->find('YOUR-SKU');
$variation = $response->json('entries.0');

// Get all images for an item
$images = $connector->images()->forItem($variation['itemId'])->json();
```

## Authentication

The SDK handles Bearer token authentication automatically. After calling `login()`, the token is stored in memory and sent with every request. If the token expires (24h), the SDK re-authenticates automatically before the next request.

```php
$connector = new PlentyOneConnector('https://your-instance.plentymarkets.com/rest');
$connector->login('username', 'password');

// All subsequent calls are authenticated
```

## Supported API Calls

### Variations

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `variations()->find(string $numberExact)` | Find variation by SKU | `GET /rest/items/variations?numberExact=...` |
| `variations()->get(int $itemId, int $variationId, ?string $with)` | Get a specific variation | `GET /rest/items/{id}/variations/{varId}` |
| `variations()->list(array $filters)` | List variations with filters | `GET /rest/items/variations` |
| `variations()->documents(int $itemId, int $variationId)` | Get file-type properties (documents) | `GET /rest/items/{id}/variations/{varId}` |
| `variations()->addDocument(int $itemId, int $variationId, int $propertyId, string $fileUrl)` | Add or update a document | `POST/PUT /rest/properties/relations` |
| `variations()->removeDocument(int $itemId, int $variationId, int $propertyId)` | Remove a document | `DELETE /rest/properties/relations/{id}` |

**Available filters for `list()`:** `numberExact`, `id`, `isActive`, `page`, `itemsPerPage`, `categoryId`, `isMain`, `with`

### Images

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `images()->forItem(int $itemId)` | Get all images of an item | `GET /rest/items/{id}/images` |
| `images()->forVariation(int $itemId, int $variationId)` | Get variation image links | `GET /rest/.../variation_images` |
| `images()->upload(...)` | Upload an image (via URL or base64) | `POST /rest/items/{id}/images/upload` |
| `images()->updatePosition(int $itemId, int $imageId, int $position)` | Change image sort order | `PUT /rest/items/{id}/images/{imgId}` |
| `images()->delete(int $itemId, int $imageId)` | Delete an image | `DELETE /rest/items/{id}/images/{imgId}` |
| `images()->linkToVariation(int $itemId, int $variationId, int $imageId)` | Link image to a variation | `POST /rest/.../variation_images` |
| `images()->unlinkFromVariation(int $itemId, int $variationId, int $imageId)` | Unlink image from a variation | `DELETE /rest/.../variation_images/{imgId}` |

#### Upload via URL

```php
$response = $connector->images()->upload(
    itemId: 668423,
    uploadUrl: 'https://example.com/image.png',
    position: 0,
    name: 'Product front',
    alternate: 'Alt text for SEO',
);
$imageId = $response->json('id');
```

#### Upload via base64

```php
$base64 = base64_encode(file_get_contents('/path/to/image.png'));

$response = $connector->images()->upload(
    itemId: 668423,
    uploadImageData: $base64,
    uploadFileName: 'product.png',
    fileType: 'png',
    position: 0,
);
$imageId = $response->json('id');
```

> **Note:** PlentyONE processes images asynchronously. Right after upload, `width`, `height`, and `size` may still be `0`. The metadata is populated after a few seconds.

### Categories

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `categories()->list(array $filters)` | List categories with filters | `GET /rest/categories` |

**Available filters for `list()`:** `type`, `with`, `page`, `itemsPerPage`, `parentId`, `lang`, `name`, `level`, `plentyId`, `linklist`, `updatedAt`, `tagId`, `metaKeywords`

### Webstores

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `webstores()->list()` | List all webstores/clients with PlentyID and name | `GET /rest/webstores` |

```php
$webstores = $connector->webstores()->list()->json();

foreach ($webstores as $ws) {
    echo $ws['storeIdentifier'] . ': ' . $ws['name'] . "\n";
}
```

### Order Referrers (Sales Channels)

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `referrers()->list()` | List all order referrers/sales channels with ID and name | `GET /rest/orders/referrers` |

```php
$referrers = $connector->referrers()->list()->json();

foreach ($referrers as $r) {
    echo $r['id'] . ': ' . ($r['backendName'] ?? $r['name']) . "\n";
}
```

### Documents (File-Properties)

Documents (manuals, data sheets, etc.) are stored as properties with `cast: "file"` on variations.

```php
// Get all documents for a variation
$docs = $connector->variations()->documents($itemId, $variationId);

// Add a document (creates property link if it doesn't exist)
$connector->variations()->addDocument(
    itemId: 668423,
    variationId: 19408,
    propertyId: 498, // e.g. 498 = manual
    fileUrl: 'https://example.com/manual.pdf',
);

// Remove a document
$connector->variations()->removeDocument(
    itemId: 668423,
    variationId: 19408,
    propertyId: 498,
);
```

## Need More?

This SDK currently covers variations, images, categories, webstores, order referrers, and document properties. If you need additional endpoints, feel free to fork the repository or submit a pull request.

## License

MIT
