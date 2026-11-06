# ticketAiAllowed()

May this ticket's content go to an AI provider?

```php
function ticketAiAllowed(PDO $conn, int $ticketId): bool
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 198.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[ticketAiConfidentialPolicy()](/code/functions/ticketAiConfidentialPolicy.md), [ticketIsConfidential()](/code/functions/ticketIsConfidential.md)

## Called from

- [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md)
- [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md)
- [api/messaging/ai_suggest_reply.php](/code/files/api/messaging/ai_suggest_reply.php.md)
- [api/messaging/ai_summary.php](/code/files/api/messaging/ai_summary.php.md)
- [api/tickets/ai_cleanup_reply.php](/code/files/api/tickets/ai_cleanup_reply.php.md)
- [api/tickets/ai_merge_summary.php](/code/files/api/tickets/ai_merge_summary.php.md)
- [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md)
- [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md)

