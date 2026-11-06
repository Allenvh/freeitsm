# contractReportLoad()

The contract, with the supplier and owner names the report shows. Returns null when there is no such contract.

```php
function contractReportLoad(PDO $conn, int $contractId, ?int $analystId = null): ?array
```

Defined in [includes/contract_report.php](/code/files/includes/contract_report.php.md), line 148.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$contractId` | `int` |  | contracts.id to load |
| `$analystId` | `?int` | `null` | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 155 | `contractReportLoad` | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartySql()](/code/functions/contractPartySql.md), [contractVisibilitySql()](/code/functions/contractVisibilitySql.md)

## Called from

- [api/contracts/email_equipment_report.php](/code/files/api/contracts/email_equipment_report.php.md)
- [contracts/equipment-report.php](/code/files/contracts/equipment-report.php.md)

