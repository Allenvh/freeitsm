# webchatPromoteToTicket()

Promote a deflect-mode conversation to a real ticket. No-op returning the existing id if already ticketed. For `$mode` 'agent' the opening message is the full transcript; for 'email' the opening body is `$aiSummary` (or a fallback) and the full transcript is attached as a .txt. Returns the ticket id.

```php
function webchatPromoteToTicket(
    PDO $conn,
    array $conversation,
    array $channel,
    string $mode,
    ?string $aiSummary = null
): int
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 534.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$conversation` | `array` |  | the webchat_conversations row; one already ticketed returns its ticket id |
| `$channel` | `array` |  | the widget/channel row |
| `$mode` | `string` |  | 'agent' (opening message is the transcript) or 'email' (the summary, transcript attached) |
| `$aiSummary` | `?string` | `null` | the AI summary for 'email' mode; null or '' falls back to the transcript |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 560 | `webchatPromoteToTicket` | **update** [emails](/code/tables/emails.md) (`has_attachments`) |  |

## Calls

[buildChannelSubject()](/code/functions/buildChannelSubject.md), [saveChannelMediaAttachment()](/code/functions/saveChannelMediaAttachment.md), [webchatInsertInbound()](/code/functions/webchatInsertInbound.md), [webchatOpenTicket()](/code/functions/webchatOpenTicket.md), [webchatTranscriptText()](/code/functions/webchatTranscriptText.md), [webchatVisitorName()](/code/functions/webchatVisitorName.md)

## Called from

- [api/webchat/escalate.php](/code/files/api/webchat/escalate.php.md)

