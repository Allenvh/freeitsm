# apiContractLookupList()

Reference lookups

```php
function apiContractLookupList(PDO $conn, string $table): void
```

Defined in [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md), line 581.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the lookup table to list (contract_statuses, payment_schedules ...) - fixed by the caller |

**Returns:** `void`

## Calls

[apiRespond()](/code/functions/apiRespond.md)

## Called from

- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractStatusesList()](/code/functions/apiContractStatusesList.md), [apiPaymentSchedulesList()](/code/functions/apiPaymentSchedulesList.md), [apiSupplierTypesList()](/code/functions/apiSupplierTypesList.md), [apiSupplierStatusesList()](/code/functions/apiSupplierStatusesList.md), [apiContractTermTabsList()](/code/functions/apiContractTermTabsList.md)

