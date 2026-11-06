# serviceErrorHttpStatus()

Map a ServiceError kind to the HTTP status the API adapter should return.

```php
function serviceErrorHttpStatus(string $kind): int
```

Defined in [includes/service_context.php](/code/files/includes/service_context.php.md), line 166.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$kind` | `string` |  | the ServiceError kind; anything unknown maps to 422 |

**Returns:** `int`

## Called from

- [api/forms/audience.php](/code/files/api/forms/audience.php.md)
- [api/forms/draft.php](/code/files/api/forms/draft.php.md)
- [api/self-service/draft.php](/code/files/api/self-service/draft.php.md)
- [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md) - in [apiFailFromService()](/code/functions/apiFailFromService.md)
- [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md) - in [apiServiceStatusFail()](/code/functions/apiServiceStatusFail.md)

