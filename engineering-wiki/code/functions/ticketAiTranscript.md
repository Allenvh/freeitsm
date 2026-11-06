# ticketAiTranscript()

Turn a ticket into the plain text a model reads.

Bodies are stripped to text before they go anywhere near a provider. Markup is
noise that costs tokens, and stripping it also means no HTML from a stranger's
email is ever echoed back into anything FreeITSM renders.

```php
function ticketAiTranscript(PDO $conn, int $ticketId, array $opts = []): array
```

Defined in [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md), line 121.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$opts` | `array` | `[]` | ['max_messages'=>int, 'max_notes'=>int, 'include_notes'=>bool] |

**Returns:** array ['text','messages','notes','last_email_id','truncated']

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 132 | `ticketAiTranscript` | read [emails](/code/tables/emails.md) |  |
| 144 | `ticketAiTranscript` | read [emails](/code/tables/emails.md) |  |
| 151 | `ticketAiTranscript` | read [ticket_notes](/code/tables/ticket_notes.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md)
- [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md)

