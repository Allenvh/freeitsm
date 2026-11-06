# intOrNull()

Helper: get int/bigint value or null

```php
function intOrNull($data, $key)
```

Defined in [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md), line 116.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$data` |  |  | one section of the agent's decoded JSON report (untrusted) |
| `$key` |  |  | the field to read; anything not numeric comes back as null |

## Called from

- [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md)

