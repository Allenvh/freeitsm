# maskEmail()

Local part masked, domain kept — the domain is the diagnostic half.

```php
function maskEmail($s)
```

Defined in [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md), line 93.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` |  |  | an email address; without an @ the whole value is masked |

## Calls

[mask()](/code/functions/mask.md)

## Called from

- [api/system/debug-tools/D010_signin_methods.php](/code/files/api/system/debug-tools/D010_signin_methods.php.md)

