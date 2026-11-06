# strOrNull()

Helper: get string value or null

```php
function strOrNull($data, $key, $maxLen = null)
```

Defined in [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md), line 105.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$data` |  |  | one section of the agent's decoded JSON report (untrusted) |
| `$key` |  |  | the field to read from it |
| `$maxLen` |  | `null` | the most characters to keep (the column length); null or 0 keeps it all |

## Called from

- [api/external/system-info/submit/index.php](/code/files/api/external/system-info/submit/index.php.md)

