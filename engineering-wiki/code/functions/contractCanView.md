# contractCanView()

May this analyst see contract `$id`? (Existence is the caller's business.)

```php
function contractCanView(PDO $conn, int $analystId, int $contractId): bool
```

Defined in [includes/contract_party.php](/code/files/includes/contract_party.php.md), line 142.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$contractId` | `int` |  | contracts.id to check; a missing contract also returns false |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 145 | `contractCanView` | read [contracts](/code/tables/contracts.md) |  |

## Calls

[contractVisibilitySql()](/code/functions/contractVisibilitySql.md)

## Called from

- [api/rfp-builder/save_rfp.php](/code/files/api/rfp-builder/save_rfp.php.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractAssetsFor()](/code/functions/contractAssetsFor.md), [contractAssetLink()](/code/functions/contractAssetLink.md), [contractAssetLoad()](/code/functions/contractAssetLoad.md), [contractAssetSearch()](/code/functions/contractAssetSearch.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSave()](/code/functions/leaseSave.md), [leaseGet()](/code/functions/leaseGet.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)

