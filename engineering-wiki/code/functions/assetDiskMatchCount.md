# assetDiskMatchCount()

How many drives across the estate look like this one, and on how many assets.

🔴 SCOPED TO WHAT THE ANALYST CAN SEE. This count is shown to a person before
they press a button, so an unscoped COUNT would tell them how many machines
another company has — the exact leak this codebase has shipped once before,
by scoping the list and forgetting the count.

```php
function assetDiskMatchCount(PDO $conn, int $analystId, ?string $model, $sizeBytes): array
```

Defined in [includes/asset_disk_visibility.php](/code/files/includes/asset_disk_visibility.php.md), line 108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$model` | `?string` |  | the drive's model to match exactly (null matches only drives with no model) |
| `$sizeBytes` |  |  | the drive's size in bytes to match exactly (null matches only drives with no size) |

**Returns:** array{drives:int,assets:int}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 110 | `assetDiskMatchCount` | read [asset_physical_disks](/code/tables/asset_physical_disks.md), read [assets](/code/tables/assets.md) |  |

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md)

## Called from

- [api/assets/count_matching_disks.php](/code/files/api/assets/count_matching_disks.php.md)

