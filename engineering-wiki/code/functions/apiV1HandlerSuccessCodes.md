# apiV1HandlerSuccessCodes()

Scan the resource files for each handler's `apiRespond()` status codes, so an operation declares the success code(s) it genuinely returns (201 on create, 200 otherwise, both where a handler upserts). Returns [handler => [codes]].

```php
function apiV1HandlerSuccessCodes(): array
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 379.

**Returns:** `array`

## Called from

- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1BuildOpenApi()](/code/functions/apiV1BuildOpenApi.md)

