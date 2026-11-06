# tableExists()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md), [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md)

Whether a table exists in this database - false on any error, so an older install reads as not ready rather than breaking the report.

```php
function tableExists(PDO $c, $table)
```

Defined in [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md), line 206.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `PDO` |  | the database connection |
| `$table` |  |  | the table name to look for |

## Called from

- [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md)
- [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md)

