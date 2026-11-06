# gapEmbedBatch()

Embed up to `$batch` not-yet-embedded tickets. Returns a progress report.

Resumable by design — every vector is cached in knowledge_gap_tickets, so a
closed tab, a timeout or a rate limit costs only the batch in flight.

```php
function gapEmbedBatch(
    PDO $conn,
    int $analystId,
    int $lookbackDays,
    int $batch,
    string $openaiKey
): array
```

Defined in [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), line 120.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$lookbackDays` | `int` |  | how many days back from now a ticket's closed_datetime may be |
| `$batch` | `int` |  | how many tickets to embed this call, clamped to 1-50 |
| `$openaiKey` | `string` |  | the OpenAI key from `knowledgeOpenAiKey()`; '' skips the batch |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 130 | `gapEmbedBatch` | read [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) |  |
| 166 | `gapEmbedBatch` | read [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) |  |

## Calls

[gapUpsertTicket()](/code/functions/gapUpsertTicket.md), [gapWindowSql()](/code/functions/gapWindowSql.md), [kbGenerateEmbedding()](/code/functions/kbGenerateEmbedding.md), [writeupEmbeddingText()](/code/functions/writeupEmbeddingText.md)

## Called from

- [api/knowledge/analyse_gaps.php](/code/files/api/knowledge/analyse_gaps.php.md)

