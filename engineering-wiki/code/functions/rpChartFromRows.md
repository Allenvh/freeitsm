# rpChartFromRows()

Turn [label, value, colour?] rows into chart data, folding everything past `limit` into one "Other" slice so a pie never has fifty slivers.

```php
function rpChartFromRows(array $rows, int $limit, string $chart, string $seriesName): array
```

Defined in [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), line 94.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rows` | `array` |  | the grouped rows, biggest first: label, value and optionally colour |
| `$limit` | `int` |  | how many slices to keep before the rest is folded into "Other" |
| `$chart` | `string` |  | the chart type, one of RP_CHART_TYPES |
| `$seriesName` | `string` |  | the series' name, e.g. "Tickets" |

**Returns:** `array`

## Calls

[t()](/code/functions/t.md)

## Called from

- [includes/report_packs/blocks_assets.php](/code/files/includes/report_packs/blocks_assets.php.md) - in [rpAssetsBreakdown()](/code/functions/rpAssetsBreakdown.md), [rpIntuneBreakdown()](/code/functions/rpIntuneBreakdown.md)
- [includes/report_packs/blocks_software.php](/code/files/includes/report_packs/blocks_software.php.md) - in [rpSoftwareByPublisher()](/code/functions/rpSoftwareByPublisher.md)
- [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) - in [rpTicketsBreakdown()](/code/functions/rpTicketsBreakdown.md)

