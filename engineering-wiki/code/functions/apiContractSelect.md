# apiContractSelect()

The SELECT for contracts, with supplier, owner, status and payment schedule names, the customer and company columns and, once Database Verification has added it, the cost centre.

```php
function apiContractSelect(PDO $conn): string
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `apiContractSelect` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 65 | `apiContractSelect` | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md), read [analysts](/code/tables/analysts.md), read [contract_statuses](/code/tables/contract_statuses.md), read [payment_schedules](/code/tables/payment_schedules.md) |  |

## Calls

[contractOwnerSql()](/code/functions/contractOwnerSql.md), [contractPartySql()](/code/functions/contractPartySql.md), [costCentreReady()](/code/functions/costCentreReady.md)

## Called from

- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiLoadContract()](/code/functions/apiLoadContract.md), [apiContractsList()](/code/functions/apiContractsList.md)

