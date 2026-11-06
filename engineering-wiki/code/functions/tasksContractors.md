# tasksContractors()

The contractor on each of these tasks: [task_id => {supplier_id, supplier_name, contact_id, contact_name, contact_email}]. Tasks without one are absent.

```php
function tasksContractors(PDO $conn, array $taskIds): array
```

Defined in [includes/task_contractors.php](/code/files/includes/task_contractors.php.md), line 57.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$taskIds` | `array` |  | task ids; non-numeric and zero values are dropped |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `tasksContractors` | read [tasks](/code/tables/tasks.md), read [suppliers](/code/tables/suppliers.md), read [contacts](/code/tables/contacts.md) |  |

## Calls

[tasksContractorReady()](/code/functions/tasksContractorReady.md), [tasksSupplierNameSql()](/code/functions/tasksSupplierNameSql.md)

## Called from

- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiTaskContractor()](/code/functions/apiTaskContractor.md)
- [includes/task_contractors.php](/code/files/includes/task_contractors.php.md) - in [tasksWithContractors()](/code/functions/tasksWithContractors.md)

