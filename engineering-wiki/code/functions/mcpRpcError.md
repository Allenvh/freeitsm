# mcpRpcError()

Sends a JSON-RPC 2.0 error response (code and message) for request `$id` with HTTP 200, as JSON-RPC expects, then stops.

```php
function mcpRpcError($id, int $code, string $message): void
```

Defined in [api/mcp/index.php](/code/files/api/mcp/index.php.md), line 75.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` |  |  | the JSON-RPC request id being answered; null when the request could not be read |
| `$code` | `int` |  | the JSON-RPC error code, e.g. -32700 parse error, -32600 invalid request |
| `$message` | `string` |  | the error message for the client |

**Returns:** `void`

## Calls

[mcpReply()](/code/functions/mcpReply.md)

## Called from

- [api/mcp/index.php](/code/files/api/mcp/index.php.md)

