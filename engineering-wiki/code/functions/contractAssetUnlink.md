# contractAssetUnlink()

Remove a link. The asset and the contract both survive it — this only ever says "this equipment is not on that agreement".

```php
function contractAssetUnlink(PDO $conn, int $analystId, int $linkId): void
```

Defined in [includes/contract_assets.php](/code/files/includes/contract_assets.php.md), line 214.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$linkId` | `int` |  | contract_assets.id to delete |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 217 | `contractAssetUnlink` | **delete** [contract_assets](/code/tables/contract_assets.md) |  |

## Calls

[contractAssetLoad()](/code/functions/contractAssetLoad.md)

## Called from

- [api/contracts/delete_contract_asset.php](/code/files/api/contracts/delete_contract_asset.php.md)

