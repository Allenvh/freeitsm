# v()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [system/sso/carddav.php](/code/files/system/sso/carddav.php.md), [system/sso/provider.php](/code/files/system/sso/provider.php.md)

Print a value into an input safely.

```php
function v($row, string $k): string
```

Defined in [system/sso/carddav.php](/code/files/system/sso/carddav.php.md), line 86.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` |  |  | the provider row being edited (an auth_providers row) |
| `$k` | `string` |  | the column to print |

**Returns:** `string`

## Called from

- [system/sso/carddav.php](/code/files/system/sso/carddav.php.md)
- [system/sso/provider.php](/code/files/system/sso/provider.php.md)

