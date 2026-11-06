# dbVerifyModuleForTable()

Which module a table belongs to. Never throws; unknown tables → 'other'.

```php
function dbVerifyModuleForTable(string $table): string
```

Defined in [includes/db_verify_modules.php](/code/files/includes/db_verify_modules.php.md), line 158.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$table` | `string` |  | a database table name, e.g. 'ticket_audit' |

**Returns:** `string`

## Called from

- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)

