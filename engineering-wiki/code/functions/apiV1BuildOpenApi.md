# apiV1BuildOpenApi()

Build the complete OpenAPI 3.0.3 document as a PHP array.

```php
function apiV1BuildOpenApi(): array
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 26.

**Returns:** `array`

## Calls

[apiV1BaseSchemas()](/code/functions/apiV1BaseSchemas.md), [apiV1BuildOperation()](/code/functions/apiV1BuildOperation.md), [apiV1HandlerSuccessCodes()](/code/functions/apiV1HandlerSuccessCodes.md), [apiV1MapEndpointsToHandlers()](/code/functions/apiV1MapEndpointsToHandlers.md), [apiV1OpenApiInfoDescription()](/code/functions/apiV1OpenApiInfoDescription.md), [apiV1PermissionCatalog()](/code/functions/apiV1PermissionCatalog.md)

## Called from

- [api/v1/dev/openapi_fix.php](/code/files/api/v1/dev/openapi_fix.php.md)
- [api/v1/dev/openapi_verify.php](/code/files/api/v1/dev/openapi_verify.php.md)
- [api/v1/lib/openapi_check.php](/code/files/api/v1/lib/openapi_check.php.md)
- [api/v1/openapi.php](/code/files/api/v1/openapi.php.md)

