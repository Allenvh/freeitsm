# apiV1OperationId()

A stable, unique operationId from method + path.

```php
function apiV1OperationId(string $method, string $path): string
```

Defined in [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md), line 328.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$method` | `string` |  | the HTTP method, e.g. 'GET' |
| `$path` | `string` |  | the path template, e.g. /tickets/{id} |

**Returns:** `string`

## Called from

- [api/v1/lib/openapi.php](/code/files/api/v1/lib/openapi.php.md) - in [apiV1BuildOperation()](/code/functions/apiV1BuildOperation.md)

