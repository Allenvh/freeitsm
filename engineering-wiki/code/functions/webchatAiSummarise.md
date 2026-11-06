# webchatAiSummarise()

Summarise a web chat transcript into a short support-ticket description for a human agent — used as the ticket body on an "email me back" escalation. Returns '' when AI is unconfigured or the call fails, so the caller can fall back to the raw transcript.

```php
function webchatAiSummarise(PDO $conn, string $transcript): string
```

Defined in [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md), line 97.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$transcript` | `string` |  | the chat as plain text (webchatTranscriptText()); blank gives '' |

**Returns:** `string`

## Calls

[aiProviderChat()](/code/functions/aiProviderChat.md), [aiSettingsLoad()](/code/functions/aiSettingsLoad.md)

## Called from

- [api/webchat/escalate.php](/code/files/api/webchat/escalate.php.md)

