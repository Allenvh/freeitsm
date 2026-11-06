# apiV1MapEndpointsToHandlers()

Match every docs endpoint (method + path template) to its handler function by testing the template against the route regexes. Returns ['M /path' => handler].

```php
function apiV1MapEndpointsToHandlers(array $routes): array
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 356.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$routes` | `array` |  | the router's table: [method, path regex, permission, handler function] per route |

**Returns:** `array`

## Called from

- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1BuildOpenApi()](/code/functions/apiV1BuildOpenApi.md)

