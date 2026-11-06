# writeupEmbeddingText()

The shorter text used for EMBEDDING a ticket (as opposed to drafting from it).

Deliberately different from the full bundle: for "is this the same question as
that one", what matters is the problem as reported, not the thousand words of
troubleshooting that followed. Feeding the whole thread in makes every long
ticket look similar to every other long ticket, and clusters collapse into
"difficult ones".

```php
function writeupEmbeddingText(PDO $conn, int $ticketId, ?string $subject = null): string
```

Defined in [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), line 369.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$subject` | `?string` | `null` | the ticket's subject if the caller has it; null reads it from tickets |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 372 | `writeupEmbeddingText` | read [tickets](/code/tables/tickets.md) |  |
| 378 | `writeupEmbeddingText` | read [emails](/code/tables/emails.md) |  |

## Calls

[writeupPlainText()](/code/functions/writeupPlainText.md)

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapEmbedBatch()](/code/functions/gapEmbedBatch.md)

