# contractAssetLoad()

One link, but only if this analyst can reach the asset on it.

```php
function contractAssetLoad(PDO $conn, int $analystId, int $linkId): array
```

Defined in [includes/contract_assets.php](/code/files/includes/contract_assets.php.md), line 226.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$linkId` | `int` |  | contract_assets.id to load |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 228 | `contractAssetLoad` | read [contract_assets](/code/tables/contract_assets.md) |  |

## Calls

[contractAssetCanReach()](/code/functions/contractAssetCanReach.md), [contractCanView()](/code/functions/contractCanView.md)

## Called from

- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractAssetSetReference()](/code/functions/contractAssetSetReference.md), [contractAssetUnlink()](/code/functions/contractAssetUnlink.md)

