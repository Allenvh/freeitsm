# d012_trim()

Mask nothing, but keep a runaway value from swamping the report.

```php
function d012_trim($s, $max = 120)
```

Defined in [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md), line 125.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` |  |  | the value to print |
| `$max` |  | `120` | the most bytes to keep before adding an ellipsis |

## Called from

- [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md) - in [d012_whyMissing()](/code/functions/d012_whyMissing.md)

