# mcpReply()

Sends an HTTP status and, when given, a JSON body, then stops.

```php
function mcpReply(int $status, ?array $body = null): void
```

Defined in [api/mcp/index.php](/code/files/api/mcp/index.php.md), line 51.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$status` | `int` |  | the HTTP status to send |
| `$body` | `?array` | `null` | the JSON body to send; null sends no body |

**Returns:** `void`

## Called from

- [api/mcp/index.php](/code/files/api/mcp/index.php.md) - in [mcpResult()](/code/functions/mcpResult.md), [mcpRpcError()](/code/functions/mcpRpcError.md)

