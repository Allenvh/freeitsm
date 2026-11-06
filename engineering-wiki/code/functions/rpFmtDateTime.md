# rpFmtDateTime()

Formats a UTC date and time in the viewer's own format and timezone; '' for none.

```php
function rpFmtDateTime(?string $utc): string
```

Defined in [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), line 311.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$utc` | `?string` |  | a UTC 'Y-m-d H:i:s' timestamp; null or '' gives '' |

**Returns:** `string`

## Calls

[fmt_datetime()](/code/functions/fmt_datetime.md)

## Called from

- [api/reporting/packs/list.php](/code/files/api/reporting/packs/list.php.md)
- [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) - in [rpStatusUptime()](/code/functions/rpStatusUptime.md), [rpStatusIncidentLog()](/code/functions/rpStatusIncidentLog.md), [rpStatusIncidentSummary()](/code/functions/rpStatusIncidentSummary.md)
- [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) - in [rpTicketsList()](/code/functions/rpTicketsList.md)

