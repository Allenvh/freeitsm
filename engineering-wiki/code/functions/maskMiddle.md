# maskMiddle()

Shortens a value to its first and last four characters (the first two for a short one), so a shared report shows enough to recognise it but not the whole value.

```php
function maskMiddle($s)
```

Defined in [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` |  |  | the value to shorten; empty gives '(empty)' |

## Called from

- [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md) - in [maskId()](/code/functions/maskId.md)

