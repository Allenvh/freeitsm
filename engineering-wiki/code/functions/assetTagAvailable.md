# assetTagAvailable()

Is this asset tag free within its company?

Application-level because a UNIQUE (tenant_id, asset_tag) index would NOT
hold for the Default company: MySQL treats NULLs as distinct in a unique
index, so two NULL-tenant assets could both be LT0001 while the index looked
like it was guarding them. Same reason hostname is checked here rather than
by the schema. `<=>` is the null-safe equality operator, so the comparison
behaves for the Default company as well as a named one.

A check in code needs serialising against a concurrent write - every caller
that WRITES a tag goes through AssetTagsService::withLock() (PR #164).

```php
function assetTagAvailable(PDO $conn, ?int $tenantId, string $tag, ?int $exceptAssetId = null): bool
```

Defined in [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), line 135.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$tag` | `string` |  | the asset tag to check, trimmed here; '' is always available |
| `$exceptAssetId` | `?int` | `null` | the asset being edited, so its own tag does not count as taken; null for a new asset |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 139 | `assetTagAvailable` | read [assets](/code/tables/assets.md) |  |

## Calls

[assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md)

## Called from

- [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md) - in `AssetTagsService::generate`, `AssetTagsService::createWithTag`, `AssetTagsService::assign`

