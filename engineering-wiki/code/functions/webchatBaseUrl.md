# webchatBaseUrl()

Public base URL + app root used to build widget asset / API URLs. Reuses the messaging public base (system setting or derived from the request) and derives the app root from SCRIPT_NAME so it works under any sub-path. Only ever called from a script under /api/webchat/, so that prefix is what we strip.

```php
function webchatBaseUrl(PDO $conn): string
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 55.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[messagingPublicBaseUrl()](/code/functions/messagingPublicBaseUrl.md)

## Called from

- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatEmbedSnippet()](/code/functions/webchatEmbedSnippet.md)

