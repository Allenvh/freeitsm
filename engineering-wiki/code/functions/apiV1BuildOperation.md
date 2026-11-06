# apiV1BuildOperation()

Build one OpenAPI Operation Object for a docs endpoint.

```php
function apiV1BuildOperation(
    array $ep,
    string $section,
    array $extras,
    array $endpointToHandler,
    array $handlerSuccess,
    array $schemas
): array
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 142.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ep` | `array` |  | one endpoint from spec.json: m (method), p (path template), s, d, params, perm |
| `$section` | `string` |  | the spec.json section the endpoint is listed under, used as its tag |
| `$extras` | `array` |  | that endpoint's entry in spec.json extras - examples and specific errors; may be empty |
| `$endpointToHandler` | `array` |  | 'METHOD /path' => handler function, from `apiV1MapEndpointsToHandlers()` |
| `$handlerSuccess` | `array` |  | handler function => the success codes its `apiRespond()` calls use |
| `$schemas` | `array` |  | the typed schemas from openapi_schemas.php: schemas, responses and requestBodies |

**Returns:** `array`

## Calls

[apiV1BuildResponses()](/code/functions/apiV1BuildResponses.md), [apiV1OperationId()](/code/functions/apiV1OperationId.md), [apiV1ParamDescription()](/code/functions/apiV1ParamDescription.md)

## Called from

- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1BuildOpenApi()](/code/functions/apiV1BuildOpenApi.md)

