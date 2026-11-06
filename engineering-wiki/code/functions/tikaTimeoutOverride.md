# tikaTimeoutOverride()

Force a shorter timeout for the current process.

⚠️ Exists for the opportunistic drain, which runs INSIDE a request somebody is
waiting on. The configured timeout is sized for a scanned document on a cron —
a minute or more — and applying that to a page load means an analyst who
happened to open the wrong screen waits a minute for it. Set low, do one item,
set back.

```php
function tikaTimeoutOverride(?int $seconds = null): ?int
```

Defined in [includes/search/tika.php](/code/files/includes/search/tika.php.md), line 78.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$seconds` | `?int` | `null` | the timeout to force for this process, or null to go back to the setting; omit to only read it |

**Returns:** `?int`

## Called from

- [includes/search/extract_queue.php](/code/files/includes/search/extract_queue.php.md) - in [extractQueueDrainOpportunistic()](/code/functions/extractQueueDrainOpportunistic.md)
- [includes/search/tika.php](/code/files/includes/search/tika.php.md) - in [tikaTimeout()](/code/functions/tikaTimeout.md)

