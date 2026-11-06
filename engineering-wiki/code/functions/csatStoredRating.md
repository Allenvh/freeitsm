# csatStoredRating()

The rating a survey holds, or null while it is unanswered.

```php
function csatStoredRating(PDO $conn, int $responseId): ?int
```

Defined in [includes/csat.php](/code/files/includes/csat.php.md), line 265.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$responseId` | `int` |  | ticket_csat_responses.id |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 267 | `csatStoredRating` | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingRecordCsatReply()](/code/functions/messagingRecordCsatReply.md)

