# warbotSystemPrompt()

The system prompt.

Written for somebody standing in a comms room with a phone in one hand. Short
answers, no preamble, and — the part that matters most — say when you do not
know. A bot that guesses during an incident is worse than one that shrugs,
because its guess gets repeated to the business.

```php
function warbotSystemPrompt(string $channelName): string
```

Defined in [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md), line 155.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$channelName` | `string` |  | the channel's display name, written into the prompt |

**Returns:** `string`

## Called from

- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotAnswer()](/code/functions/warbotAnswer.md)

