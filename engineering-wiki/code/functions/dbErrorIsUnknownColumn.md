# dbErrorIsUnknownColumn()

"That column doesn't exist." MySQL: SQLSTATE 42S22, driver code 1054.

```php
function dbErrorIsUnknownColumn(PDOException $e): bool
```

Defined in [includes/db_errors.php](/code/files/includes/db_errors.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$e` | `PDOException` |  | the exception a query threw |

**Returns:** `bool`

## Called from

- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)
- [includes/db_errors.php](/code/files/includes/db_errors.php.md) - in [dbErrorIsMissingSchema()](/code/functions/dbErrorIsMissingSchema.md)

