# rpPercent()

100% / 99.13% / 95.7% - two decimals, trailing zeros dropped.

```php
function rpPercent(float $p): string
```

Defined in [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md), line 87.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `float` |  | a percentage, 0-100 |

**Returns:** `string`

## Called from

- [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md) - in [rpStatusUptime()](/code/functions/rpStatusUptime.md), [rpStatusUptimeTable()](/code/functions/rpStatusUptimeTable.md), [rpStatusKpis()](/code/functions/rpStatusKpis.md)

