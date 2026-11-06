# maskId()

Partially masks a client or tenant id - GUIDs and long opaque ids through `maskMiddle()`, short readable names shown as they are.

```php
function maskId($s)
```

Defined in [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md), line 125.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` |  |  | a client or tenant id; empty gives '(empty!)' |

## Calls

[maskMiddle()](/code/functions/maskMiddle.md)

## Called from

- [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md)

