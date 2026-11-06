# extractQueueDrain()

Read up to `$limit` pending attachments.

⚠️ Re-indexes the whole TICKET for each one rather than writing the corpus row
directly. That keeps a single definition of what a ticket's rows are (see
searchIndexTicket) — and since the extracted text is cached in
`attachment_text`, re-reading the ticket's other attachments costs nothing.

Never throws. Returns what it managed.

```php
function extractQueueDrain(PDO $conn, int $limit, float $deadline = 0): array
```

Defined in [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md), line 85.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$limit` | `int` |  | the most pending attachments to claim this pass (at least 1) |
| `$deadline` | `float` | `0` | a microtime(true) moment to stop starting new tickets at; 0 means no time limit |

**Returns:** array{done:int,still_pending:int,skipped_reason:string}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 107 | `extractQueueDrain` | read [attachment_text](/code/tables/attachment_text.md), read [email_attachments](/code/tables/email_attachments.md) |  |
| 119 | `extractQueueDrain` | **update** [attachment_text](/code/tables/attachment_text.md) (`status`) |  |
| 129 | `extractQueueDrain` | **update** [attachment_text](/code/tables/attachment_text.md) (`status`) |  |
| 137 | `extractQueueDrain` | read [attachment_text](/code/tables/attachment_text.md) |  |
| 159 | `extractQueueDrain` | **update** [attachment_text](/code/tables/attachment_text.md) (`status`, `extracted_datetime`) |  |
| 172 | `extractQueueDrain` | read [attachment_text](/code/tables/attachment_text.md), read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |

## Calls

[extractQueueDepth()](/code/functions/extractQueueDepth.md), [extractQueueDepthUnchanged()](/code/functions/extractQueueDepthUnchanged.md), [searchIndexTicket()](/code/functions/searchIndexTicket.md), [tikaConfigured()](/code/functions/tikaConfigured.md), [tikaHandles()](/code/functions/tikaHandles.md)

## Called from

- [cron/attachment_extract.php](/code/files/cron/attachment_extract.php.md)
- [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md) - in [extractQueueDrainOpportunistic()](/code/functions/extractQueueDrainOpportunistic.md)

