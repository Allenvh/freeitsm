# oaf_resolvePath()

Turns a path template into a real path by replacing each {id} with the first id its parent collection returns - null when there is no row to use. Ids are cached per collection.

```php
function oaf_resolvePath($template, $base, $key)
```

Defined in [api/v1/dev/openapi_fix.php](/code/files/api/v1/dev/openapi_fix.php.md), line 120.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$template` |  |  | an OpenAPI path template, e.g. /tickets/{id}/notes |
| `$base` |  |  | the API's base URL, e.g. http://localhost/freeitsm-app/api/v1/index.php |
| `$key` |  |  | the API key, sent as a bearer token |

## Calls

[oaf_get()](/code/functions/oaf_get.md)

## Called from

- [api/v1/dev/openapi_fix.php](/code/files/api/v1/dev/openapi_fix.php.md)

