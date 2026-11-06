# redact_row()

Replaces the personal values in a row (names, email, username, password hash, MFA secret) with "[redacted N chars]", so the report can be shared.

```php
function redact_row(
    $row,
    $redactCols = ['password_hash', 'totp_secret', 'username', 'email', 'full_name', 'display_name', 'preferred_name']
)
```

Defined in [api/system/debug-tools/D001_demo_core_import.php](/code/files/api/system/debug-tools/D001_demo_core_import.php.md), line 59.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` |  |  | a database row as an array; anything else is returned untouched |
| `$redactCols` |  | `['password_hash', 'totp_secret', 'username', 'email', 'full_name', 'display_name', 'preferred_name']` | the column names whose values are replaced |

## Called from

- [api/system/debug-tools/D001_demo_core_import.php](/code/files/api/system/debug-tools/D001_demo_core_import.php.md)

