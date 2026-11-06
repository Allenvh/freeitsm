# rpResolveRange()

Resolve a pack's range into local dates (for display) and UTC bounds (for SQL). The end is EXCLUSIVE: [from, to). A custom range is inclusive of both days the reader picked, so its end is the day after.

```php
function rpResolveRange(array $range, ?string $tz = null): array
```

Defined in [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md), line 35.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$range` | `array` |  | the pack's range as saved: preset (an RP_RANGE_PRESETS value) and, for 'custom', from and to as 'Y-m-d' |
| `$tz` | `?string` | `null` | the timezone to read the range in; null means the viewer's own (Tz::current()) |

**Returns:** array{preset:string,from_date:string,to_date:string,from_utc:string,to_utc:string,days:int,tz:string}

## Calls

[rpParseDate()](/code/functions/rpParseDate.md)

## Called from

- [api/reporting/packs/context.php](/code/files/api/reporting/packs/context.php.md)
- [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) - in [rpBlockData()](/code/functions/rpBlockData.md)

