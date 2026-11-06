# apiExtractKey()

Pull the key from Authorization: Bearer <key>, a raw Authorization value, or X-Api-Key.

```php
function apiExtractKey(): ?string
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 73.

**Returns:** `?string`

## Called from

- [api/mcp/index.php](/code/files/api/mcp/index.php.md)
- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiAuthenticate()](/code/functions/apiAuthenticate.md)

