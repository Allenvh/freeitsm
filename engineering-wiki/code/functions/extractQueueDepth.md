# extractQueueDepth()

How many attachments are waiting.

```php
function extractQueueDepth(PDO $conn): int
```

Defined in [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md), line 61.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `extractQueueDepth` | read [attachment_text](/code/tables/attachment_text.md) |  |

## Called from

- [api/system/tika_settings.php](/code/files/api/system/tika_settings.php.md)
- [cron/attachment_extract.php](/code/files/cron/attachment_extract.php.md)
- [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md) - in [extractQueueDrain()](/code/functions/extractQueueDrain.md), [extractQueueDrainOpportunistic()](/code/functions/extractQueueDrainOpportunistic.md)

