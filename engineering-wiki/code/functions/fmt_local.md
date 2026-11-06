# fmt_local()

Format a UTC datetime (as stored in the DB) in the current analyst's display zone. Returns '' for null/empty and passes the raw value through unchanged if it can't be parsed, so a bad value is visible rather than fatal.

```php
function fmt_local(?string $utc, string $format = 'Y-m-d H:i'): string
```

Defined in [includes/timezone.php](/code/files/includes/timezone.php.md), line 118.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$utc` | `?string` |  | A UTC datetime string (e.g. '2026-07-05 14:30:00') or null. |
| `$format` | `string` | `'Y-m-d H:i'` | A `date()` format string. |

**Returns:** `string`

## Called from

- [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md) - in [buildFullEmailBody()](/code/functions/buildFullEmailBody.md)
- [includes/services/handover_templates.php](/code/files/includes/services/handover_templates.php.md) - in `HandoverTemplates::renderAssetTable`
- [includes/template_email.php](/code/files/includes/template_email.php.md) - in [buildTicketMergeData()](/code/functions/buildTicketMergeData.md)

