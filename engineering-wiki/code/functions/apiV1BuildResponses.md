# apiV1BuildResponses()

Assemble the responses map: success code(s) + every error the endpoint can return.

```php
function apiV1BuildResponses(
    array $ep,
    array $extras,
    array $endpointToHandler,
    array $handlerSuccess,
    array $schemas
): array
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 233.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ep` | `array` |  | one endpoint from spec.json: m (method), p (path template), s, d, params, perm |
| `$extras` | `array` |  | that endpoint's entry in spec.json extras - examples and specific errors; may be empty |
| `$endpointToHandler` | `array` |  | 'METHOD /path' => handler function, from `apiV1MapEndpointsToHandlers()` |
| `$handlerSuccess` | `array` |  | handler function => the success codes its `apiRespond()` calls use |
| `$schemas` | `array` |  | the typed schemas from openapi_schemas.php: schemas, responses and requestBodies |

**Returns:** `array`

## Calls

[apiV1EndpointErrors()](/code/functions/apiV1EndpointErrors.md), [apiV1SuccessEnvelope()](/code/functions/apiV1SuccessEnvelope.md)

## Called from

- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1BuildOperation()](/code/functions/apiV1BuildOperation.md)

