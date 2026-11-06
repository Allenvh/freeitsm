# ticketAiSummarySystemPrompt()

The instructions for the standing summary.

Two rules carry most of the weight, and both are about what NOT to do. A
summary that invents a fix is worse than no summary, because it reads exactly
like one that did not. And a summary that hides its own uncertainty removes
the only signal telling somebody to go and read the thread themselves.

```php
function ticketAiSummarySystemPrompt(): string
```

Defined in [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md), line 254.

**Returns:** `string`

## Called from

- [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md)

