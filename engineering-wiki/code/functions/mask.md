# mask()

> [!NOTE]
> Defined in 2 places (each behind a function_exists() check, or in files never loaded together): [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md), [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md)

Masks a value for a report that will be sent on - all stars when very short, otherwise the first one or two characters (and the last, for a long value) with stars between.

```php
function mask($s)
```

Defined in [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md), line 80.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` |  |  | the value to mask; empty gives (empty) |

## Called from

- [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md) - in [maskEmail()](/code/functions/maskEmail.md), [maskDn()](/code/functions/maskDn.md), [maskHost()](/code/functions/maskHost.md)
- [api/system/debug-tools/D011_directory_sync_readiness.php](/code/files/api/system/debug-tools/D011_directory_sync_readiness.php.md) - in [maskDn()](/code/functions/maskDn.md), [storedValue()](/code/functions/storedValue.md)

