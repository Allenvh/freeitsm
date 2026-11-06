# oaf_get()

GETs one API URL with the bearer key and returns [HTTP code, decoded JSON].

```php
function oaf_get($url, $key)
```

Defined in [api/v1/dev/openapi_fix.php](/code/files/api/v1/dev/openapi_fix.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` |  |  | the full API URL to fetch |
| `$key` |  |  | the API key, sent as a bearer token |

## Called from

- [api/v1/dev/openapi_fix.php](/code/files/api/v1/dev/openapi_fix.php.md) - in [oaf_resolvePath()](/code/functions/oaf_resolvePath.md)

