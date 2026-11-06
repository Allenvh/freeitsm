# extractQueueDrainOpportunistic()

A few items, on the back of a request somebody made anyway.

Called from places an analyst already waits a moment: it must stay small and
must never be allowed to make that request feel slow. Silent by design.

```php
function extractQueueDrainOpportunistic(PDO $conn): void
```

Defined in [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md), line 239.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[extractQueueDepth()](/code/functions/extractQueueDepth.md), [extractQueueDrain()](/code/functions/extractQueueDrain.md), [extractQueueSettingOn()](/code/functions/extractQueueSettingOn.md), [tikaConfigured()](/code/functions/tikaConfigured.md), [tikaTimeoutOverride()](/code/functions/tikaTimeoutOverride.md)

## Called from

- [api/system/search_status.php](/code/files/api/system/search_status.php.md)

