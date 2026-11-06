# mcpResult()

Sends a JSON-RPC 2.0 success response for request `$id`, then stops.

```php
function mcpResult($id, $result): void
```

Defined in [api/mcp/index.php](/code/files/api/mcp/index.php.md), line 66.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` |  |  | the JSON-RPC request id being answered (string, number or null), echoed back as given |
| `$result` |  |  | the method's result, sent as the response's result member |

**Returns:** `void`

## Calls

[mcpReply()](/code/functions/mcpReply.md)

## Called from

- [api/mcp/index.php](/code/files/api/mcp/index.php.md)

