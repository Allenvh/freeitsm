# aiAzureApiVersionLooksValid()

Whether an API version looks like one Azure publishes.

⚠️ GH #171: the portal shows a DATE beside every model - gpt-5-nano's is
2025-08-07 - and it is the model's version, not an API version. It has exactly
the shape of one, Azure answers it with a bare 404 "Resource not found", and
nothing on that screen says which box was wrong. Used only to EXPLAIN a
failure, never to refuse a value: Azure publishes new versions and this list
will fall behind, which costs nothing because a working call never asks.

```php
function aiAzureApiVersionLooksValid(string $v): bool
```

Defined in [includes/ai_provider.php](/code/files/includes/ai_provider.php.md), line 262.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` | `string` |  | the api-version as typed by the administrator; '' counts as valid (the default is used) |

**Returns:** `bool`

## Called from

- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md)
- [includes/ai_provider.php](/code/files/includes/ai_provider.php.md) - in [aiExplainFailure()](/code/functions/aiExplainFailure.md)

