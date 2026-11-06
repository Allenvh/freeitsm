# analystLcColumnExists()

Does this table.column exist on this install? Installs differ (a module's tables arrive with Database Verification), so every query checks first.

```php
function analystLcColumnExists(PDO $conn, string $table, string $col): bool
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 326.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | a table name from the registry (trusted) |
| `$col` | `string` |  | a column of it (trusted) |

**Returns:** `bool`

## Called from

- [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) - in [analystLcCount()](/code/functions/analystLcCount.md), [analystLcIds()](/code/functions/analystLcIds.md), [analystRemovePersonal()](/code/functions/analystRemovePersonal.md), [analystAnonymise()](/code/functions/analystAnonymise.md)

