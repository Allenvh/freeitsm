# apiJsonBody()

Parse the JSON request body. Returns [] for an empty body; 400s on a body that is present but not valid JSON (silent garbage-in is worse than an error).

```php
function apiJsonBody(): array
```

Defined in [api/v1/lib/response.php](/code/files/api/v1/lib/response.php.md), line 64.

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/index.php](/code/files/api/v1/index.php.md)

