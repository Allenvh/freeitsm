# recordPreviewContract()

── Contract ────────────────────────────────────────────────────────────────

```php
function recordPreviewContract(PDO $conn, int $analystId, int $id): ?array
```

Defined in [includes/record_preview.php](/code/files/includes/record_preview.php.md), line 362.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the contracts id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 370 | `recordPreviewContract` | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [contract_statuses](/code/tables/contract_statuses.md) |  |

## Calls

[contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md), [rpField()](/code/functions/rpField.md), [rpFields()](/code/functions/rpFields.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

