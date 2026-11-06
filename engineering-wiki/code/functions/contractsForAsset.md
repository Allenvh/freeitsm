# contractsForAsset()

Contracts covering an asset.

Filtered by `contractVisibilitySql()` for the viewer: a contract in a company
they cannot reach is left out (a link made before 3.5.0 can cross companies).

```php
function contractsForAsset(PDO $conn, int $assetId, ?int $analystId = null): array
```

Defined in [includes/contract_assets.php](/code/files/includes/contract_assets.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `?int` | `null` | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 111 | `contractsForAsset` | read [contract_assets](/code/tables/contract_assets.md), read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [contract_statuses](/code/tables/contract_statuses.md) |  |

## Calls

[contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md)

## Called from

- [api/assets/get_asset_contracts.php](/code/files/api/assets/get_asset_contracts.php.md)

