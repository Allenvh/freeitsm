# replyCleanupToneDescription()

Map a tone name (Friendly / Formal / Brief) to the short clause inserted into the system prompt. Defaults to Friendly for unrecognised values.

```php
function replyCleanupToneDescription(string $tone): string
```

Defined in [includes/reply_cleanup_prompt.php](/code/files/includes/reply_cleanup_prompt.php.md), line 16.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tone` | `string` |  | the tone name from settings: 'Friendly', 'Formal' or 'Brief'; anything else is treated as Friendly |

**Returns:** `string`

## Called from

- [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md)
- [api/tickets/get_reply_cleanup_settings.php](/code/files/api/tickets/get_reply_cleanup_settings.php.md)

