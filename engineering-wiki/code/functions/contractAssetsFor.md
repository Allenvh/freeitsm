# contractAssetsFor()

Equipment covered by a contract, filtered to what this analyst may see.

```php
function contractAssetsFor(PDO $conn, int $analystId, int $contractId): array
```

Defined in [includes/contract_assets.php](/code/files/includes/contract_assets.php.md), line 79.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$contractId` | `int` |  | contracts.id; a contract the analyst cannot view gives an empty list |

**Returns:** `array`

## Calls

[contractAssetReachSql()](/code/functions/contractAssetReachSql.md), [contractAssetSelect()](/code/functions/contractAssetSelect.md), [contractCanView()](/code/functions/contractCanView.md)

## Called from

- [api/contracts/email_equipment_report.php](/code/files/api/contracts/email_equipment_report.php.md)
- [api/contracts/get_contract_assets.php](/code/files/api/contracts/get_contract_assets.php.md)
- [contracts/equipment-report.php](/code/files/contracts/equipment-report.php.md)

