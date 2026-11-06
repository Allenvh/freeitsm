# rpFmtDate()

A LOCAL Y-m-d (already in the viewer's zone) in their date format.

```php
function rpFmtDate(string $ymd): string
```

Defined in [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), line 321.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ymd` | `string` |  | a 'Y-m-d' date already in the viewer's timezone |

**Returns:** `string`

## Called from

- [api/reporting/packs/context.php](/code/files/api/reporting/packs/context.php.md)
- [includes/report_packs/blocks_assets.php](/code/files/includes/report_packs/blocks_assets.php.md) - in [rpAssetsExpiring()](/code/functions/rpAssetsExpiring.md)
- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectsStatus()](/code/functions/rpProjectsStatus.md), [rpProjectsMilestones()](/code/functions/rpProjectsMilestones.md)
- [includes/report_packs/blocks_software.php](/code/files/includes/report_packs/blocks_software.php.md) - in [rpSoftwareLicences()](/code/functions/rpSoftwareLicences.md)
- [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) - in [rpStatusUptime()](/code/functions/rpStatusUptime.md)
- [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) - in [rpTicketsKpis()](/code/functions/rpTicketsKpis.md)

