# okbad()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md), [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md)

Formats a boolean as one of two words - OK or MISSING unless others are given.

```php
function okbad($v, $ok = 'OK', $bad = 'MISSING')
```

Defined in [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md), line 55.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` |  |  | any value, judged as true or false |
| `$ok` |  | `'OK'` | the word printed when `$v` is true |
| `$bad` |  | `'MISSING'` | the word printed when `$v` is false |

## Called from

- [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md)
- [api/system/debug-tools/D004_local_login.php](/code/files/api/system/debug-tools/D004_local_login.php.md)

