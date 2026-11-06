# ticketAiReadSystemPrompt()

"Read this ticket for me" asks a different question from the summary.

The summary is a standing description of the state. This is what you want at
the moment you open a ticket cold: what happened, and what should I do next.
It is allowed to suggest — and required to label a suggestion as one, because
the failure mode here is somebody acting on a confident guess.

```php
function ticketAiReadSystemPrompt(): string
```

Defined in [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md), line 284.

**Returns:** `string`

## Called from

- [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md)

