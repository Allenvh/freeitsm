# tikaConfigured()

Is tier 2 switched on at all?

```php
function tikaConfigured(PDO $conn): bool
```

Defined in [includes/search/tika.php](/code/files/includes/search/tika.php.md), line 101.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[tikaUrl()](/code/functions/tikaUrl.md)

## Called from

- [cron/attachment_extract.php](/code/files/cron/attachment_extract.php.md)
- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [documentTextReadable()](/code/functions/documentTextReadable.md)
- [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md) - in [extractQueueDrain()](/code/functions/extractQueueDrain.md), [extractQueueDrainOpportunistic()](/code/functions/extractQueueDrainOpportunistic.md)
- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexTicketAttachments()](/code/functions/searchIndexTicketAttachments.md)
- [system/integrations/index.php](/code/files/system/integrations/index.php.md)

