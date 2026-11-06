# buildReplyCleanupSystemPrompt()

Build the system prompt. `$customInstructions` is appended verbatim under a dedicated heading at the end so any user-supplied rules sit AFTER the safety scaffold rather than overriding it.

```php
function buildReplyCleanupSystemPrompt(
    string $greetingName,
    string $toneDescription,
    string $customInstructions = ''
): string
```

Defined in [includes/reply_cleanup_prompt.php](/code/files/includes/reply_cleanup_prompt.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$greetingName` | `string` |  | the name after "Dear" - the requester's, or a placeholder on the settings page |
| `$toneDescription` | `string` |  | the tone clause from `replyCleanupToneDescription()` |
| `$customInstructions` | `string` | `''` | the organisation's extra rules from settings, appended last; '' for none |

**Returns:** `string`

## Called from

- [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md)
- [api/tickets/get_reply_cleanup_settings.php](/code/files/api/tickets/get_reply_cleanup_settings.php.md)

