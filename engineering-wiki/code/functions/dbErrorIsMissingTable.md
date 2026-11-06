# dbErrorIsMissingTable()

"That table doesn't exist." MySQL: SQLSTATE 42S02, driver code 1146.

```php
function dbErrorIsMissingTable(PDOException $e): bool
```

Defined in [includes/db_errors.php](/code/files/includes/db_errors.php.md), line 26.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$e` | `PDOException` |  | the exception a query threw |

**Returns:** `bool`

## Called from

- [includes/db_errors.php](/code/files/includes/db_errors.php.md) - in [dbErrorIsMissingSchema()](/code/functions/dbErrorIsMissingSchema.md)
- [includes/setup_state.php](/code/files/includes/setup_state.php.md) - in [installIsUnprovisioned()](/code/functions/installIsUnprovisioned.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [tenancyTablesReady()](/code/functions/tenancyTablesReady.md), [tenancyColumnExists()](/code/functions/tenancyColumnExists.md)

