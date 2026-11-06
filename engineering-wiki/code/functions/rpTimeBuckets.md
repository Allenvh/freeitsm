# rpTimeBuckets()

Buckets for a time series over the range: day, week (Monday) or month, chosen from the range's length when 'auto'. Returned as local-date keys with labels.

```php
function rpTimeBuckets(array $range, string $grouping): array
```

Defined in [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), line 125.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$range` | `array` |  | the pack's range from `rpResolveRange()` - days, tz, from_date and to_date are used |
| `$grouping` | `string` |  | 'day', 'week', 'month', or 'auto' to pick one from the range's length |

**Returns:** array{0:string,1:array<string,string>}  [grouping, key => label]

## Calls

[rpBucketKey()](/code/functions/rpBucketKey.md)

## Called from

- [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) - in [rpTicketsTrend()](/code/functions/rpTicketsTrend.md)

