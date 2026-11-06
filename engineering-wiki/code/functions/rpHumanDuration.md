# rpHumanDuration()

A number of seconds as a short duration: 45s, 12m, 3h 5m, 2d 4h.

```php
function rpHumanDuration(int $seconds): string
```

Defined in [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), line 333.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$seconds` | `int` |  | the length of time in seconds |

**Returns:** `string`

## Called from

- [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) - in [rpStatusUptime()](/code/functions/rpStatusUptime.md), [rpStatusUptimeTable()](/code/functions/rpStatusUptimeTable.md), [rpStatusIncidentSummary()](/code/functions/rpStatusIncidentSummary.md), [rpStatusKpis()](/code/functions/rpStatusKpis.md)
- [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) - in [rpTicketsKpis()](/code/functions/rpTicketsKpis.md)

