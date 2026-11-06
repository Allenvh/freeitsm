# projectChatAiFail()

A provider problem in words the panel can show (as reports.php).

```php
function projectChatAiFail(RuntimeException $e): void
```

Defined in [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md), line 26.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$e` | `RuntimeException` |  | the AI call's failure; the message 'not_configured' means no provider is set up |

**Returns:** `void`

## Calls

[projectApiOk()](/code/functions/projectApiOk.md)

## Called from

- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)

