# extractQueueDepthUnchanged()

Did the attachments we just tried stay pending? If so the extractor is unreachable again and there is no point continuing this pass.

```php
function extractQueueDepthUnchanged(PDO $conn, array $rows): bool
```

Defined in [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md), line 219.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rows` | `array` |  | the claimed rows just processed, each with an attachment_id |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 224 | `extractQueueDepthUnchanged` | read [attachment_text](/code/tables/attachment_text.md) |  |

## Called from

- [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md) - in [extractQueueDrain()](/code/functions/extractQueueDrain.md)

