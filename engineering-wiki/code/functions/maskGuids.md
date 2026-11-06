# maskGuids()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md), [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md)

Identifiers (client IDs, tenant GUIDs) aren't secrets, but this report is made to be shared with support — so partially mask them: enough to eyeball- verify, not the full value. Diagnostic signals (match/reachability) are computed on the real values and shown as YES/NO, so nothing is lost.

```php
function maskGuids($s)
```

Defined in [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md), line 114.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` |  |  | text that may contain GUIDs; each one is shortened, the rest is left alone |

## Called from

- [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md) - in [endpointLine()](/code/functions/endpointLine.md)
- [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md)

