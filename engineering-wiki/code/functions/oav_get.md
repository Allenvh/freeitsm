# oav_get()

GETs one API URL with the bearer key and returns [HTTP code, decoded JSON].

```php
function oav_get($url, $key)
```

Defined in [api/v1/dev/openapi_verify.php](/code/files/api/v1/dev/openapi_verify.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` |  |  | the full API URL to fetch |
| `$key` |  |  | the API key, sent as a bearer token |

## Called from

- [api/v1/dev/openapi_verify.php](/code/files/api/v1/dev/openapi_verify.php.md) - in [oav_resolvePath()](/code/functions/oav_resolvePath.md)

