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
$variation = $connector->variations()->find('YOUR-SKU')->json('entries.0');

// Find a variation by EAN/barcode
$variation = $connector->variations()->list(['barcode' => '4002516915737'])->json('entries.0');

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

The SDK exposes resources via the connector. Each resource returns a Saloon `Response` object that you can call `->json()` on (or `->json('path.to.value')` for dot-notation access).

### Items

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `items()->get(int $itemId, ?string $with, ?string $lang)` | Get a single item (incl. texts) | `GET /rest/items/{id}` |
| `items()->list(...)` | List items with filters | `GET /rest/items` |

**Available filters for `list()`:** `id`, `name`, `manufacturerId`, `flagOne`, `flagTwo`, `page`, `itemsPerPage`, `with`, `lang`, `updatedBetween`, `variationUpdatedBetween`, `variationRelatedUpdatedBetween`, `or`

```php
// Item with German texts
$item = $connector->items()->get(699331)->json();
$name = $item['texts'][0]['name1'] ?? null;

// Items by manufacturer
$items = $connector->items()->list(manufacturerId: '1', itemsPerPage: 50)->json();
```

### Variations

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `variations()->find(string $numberExact)` | Find variation by SKU | `GET /rest/items/variations?numberExact=...` |
| `variations()->get(int $itemId, int $variationId, ?string $with)` | Get a specific variation | `GET /rest/items/{id}/variations/{varId}` |
| `variations()->list(array $filters)` | List variations with filters | `GET /rest/items/variations` |
| `variations()->documents(int $itemId, int $variationId)` | Get file-type properties (documents) | `GET /rest/items/{id}/variations/{varId}` |
| `variations()->addDocument(int $itemId, int $variationId, int $propertyId, string $fileUrl)` | Add or update a document | `POST/PUT /rest/properties/relations` |
| `variations()->removeDocument(int $itemId, int $variationId, int $propertyId)` | Remove a document | `DELETE /rest/properties/relations/{id}` |

**Available filters for `list()`:** `numberExact`, `id`, `itemId`, `isActive`, `isMain`, `isBundle`, `page`, `itemsPerPage`, `categoryId`, `plentyId`, `referrerId`, `manufacturerId`, `supplierId`, `variationTagId`, `storeSpecial`, `with`, `lang`, `numberFuzzy`, `barcode`, `itemName`, `itemDescription`, `flagOne`, `flagTwo`, `sku`, `supplierNumber`, `supplierNumberFuzzy`, `updatedBetween`, `createdBetween`, `relatedUpdatedBetween`, `stockWarehouseId`

**Useful `with` relations:** `item`, `variationBarcodes`, `variationCategories`, `variationDefaultCategory`, `variationSalesPrices`, `variationSuppliers`, `variationShippingProfiles`, `variationClients`, `variationMarkets`, `tags`, `properties`, `variationProperties`

The default variation response already includes `purchasePrice` (current EK netto), `movingAveragePrice` (gleitender Durchschnitts-EK), `weightG`, `weightNetG`, `widthMM`, `lengthMM`, `heightMM` and many more fields without any `with` parameter.

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
| `categories()->get(int $id, ?string $with, ?string $lang, ...)` | Get a single category by ID | `GET /rest/categories/{id}` |

**Available filters for `list()`:** `type`, `with`, `page`, `itemsPerPage`, `parentId`, `lang`, `name`, `level`, `plentyId`, `linklist`, `updatedAt`, `tagId`, `metaKeywords`

```php
// Single category with German details
$response = $connector->categories()->get(4737, with: 'details', lang: 'de');
$category = $response->json('entries.0');
echo $category['details'][0]['name']; // "Miele"
echo $category['parentCategoryId'];   // 1793
```

> **Note:** `categories()->get()` returns a paginated response with one entry — even for a single-ID lookup. Always grab `entries.0`.

### Manufacturers

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `manufacturers()->get(int $id)` | Get a single manufacturer by ID | `GET /rest/items/manufacturers/{id}` |

```php
$itemId = 699331;
$item   = $connector->items()->get($itemId)->json();
$manu   = $connector->manufacturers()->get($item['manufacturerId'])->json();
echo $manu['name']; // "Miele"
```

### Accounts (Contacts / Suppliers)

Suppliers in PlentyONE are stored as contacts with `typeId = 4`.

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `accounts()->getContact(int $contactId, ?string $with)` | Get a single contact (supplier, customer, …) | `GET /rest/accounts/contacts/{id}` |

```php
// Resolve a supplier name from variationSuppliers
$variation = $connector->variations()->get($itemId, $varId, 'variationSuppliers')->json();
foreach ($variation['variationSuppliers'] as $sup) {
    $contact = $connector->accounts()->getContact($sup['supplierId'], 'options')->json();
    echo trim($contact['firstName'] . ' ' . $contact['lastName']) . "\n";
}
```

### Stock

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `stock()->list(?int $variationId, ?string $updatedAtFrom, ...)` | List stock with filters | `GET /rest/stockmanagement/stock` |
| `stock()->forVariation(int $variationId)` | Convenience: stock entries for one variation | `GET /rest/stockmanagement/stock?variationId=...` |
| `stock()->warehouses()` | List all warehouses (id, name, type, …) | `GET /rest/stockmanagement/warehouses` |

```php
// Net stock for one variation across all warehouses
$entries  = $connector->stock()->forVariation(35747)->json('entries');
$netTotal = array_sum(array_column($entries, 'netStock'));

// Find the "Leverkusen" warehouse and get its net stock
$warehouses   = $connector->stock()->warehouses()->json();
$leverkusenId = null;
foreach ($warehouses as $wh) {
    if (stripos($wh['name'], 'Leverkusen') !== false) {
        $leverkusenId = $wh['id'];
        break;
    }
}
foreach ($entries as $e) {
    if ($e['warehouseId'] === $leverkusenId) {
        echo "Leverkusen netto: {$e['netStock']}\n";
    }
}
```

### Shipping

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `shipping()->presets(...)` | List shipping presets (profiles) | `GET /rest/orders/shipping/presets` |
| `shipping()->forItem(int $itemId)` | Item shipping profiles for one item | `GET /rest/items/{id}/item_shipping_profiles` |
| `shipping()->allItemProfiles()` | All item shipping profile links | `GET /rest/items/item_shipping_profiles` |

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

### Properties

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `properties()->list(?int $page, ?int $itemsPerPage, ?string $with)` | List all properties (paginated) | `GET /rest/properties` |
| `properties()->groups(?int $page, ?int $itemsPerPage, ?string $with)` | List all property groups (paginated) | `GET /rest/properties/groups` |

```php
$response   = $connector->properties()->list(page: 1, itemsPerPage: 100, with: 'names');
$properties = $response->json('entries');

$response = $connector->properties()->groups(page: 1, itemsPerPage: 100, with: 'names');
$groups   = $response->json('entries');
```

### Tags

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `tags()->list(?int $page, ?int $itemsPerPage, ?string $with)` | List all tags | `GET /rest/tags` |
| `tags()->link(int $tagId, string $tagType, int $relationshipValue)` | Link a tag to a variation/item | `POST /rest/tags/relationships` |
| `tags()->unlink(int $tagId, string $tagType, int $relationshipValue)` | Unlink a tag from a variation/item | `DELETE /rest/tags/relationships` |

### Catalogs

| Method | Description | API Endpoint |
|--------|-------------|-------------|
| `catalogs()->list()` | List all catalogs | `GET /rest/catalogs` |
| `catalogs()->get(string $id)` | Get a single catalog | `GET /rest/catalogs/{id}` |
| `catalogs()->preview(string $id)` | Preview catalog data | `GET /rest/catalogs/{id}/preview` |
| `catalogs()->publicUrl(string $id)` | Get the public download URL | `GET /rest/catalogs/{id}/public-url` |
| `catalogs()->privateUrl(string $id)` | Get the private download URL | `GET /rest/catalogs/{id}/private-url` |
| `catalogs()->export(string $id)` | Trigger an export run | `POST /rest/catalogs/{id}/export` |
| `catalogs()->versions(string $id)` | List all versions of a catalog | `GET /rest/catalogs/{id}/versions` |
| `catalogs()->version(string $id, string $versionId)` | Get a specific catalog version | `GET /rest/catalogs/{id}/versions/{versionId}` |
| `catalogs()->templates()` | List all catalog templates | `GET /rest/catalogs/templates` |

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

If you need additional endpoints, feel free to fork the repository or submit a pull request. The SDK follows a consistent Saloon pattern: one `Request` class per endpoint, grouped under a thin `Resource` class on the connector.

## License

MIT
