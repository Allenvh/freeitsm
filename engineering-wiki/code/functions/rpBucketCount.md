# rpBucketCount()

Count UTC timestamps into local buckets.

```php
function rpBucketCount(array $stamps, array $range, string $grouping, array $keys): array
```

Defined in [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), line 165.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$stamps` | `array` |  | the UTC timestamps to count, 'Y-m-d H:i:s' |
| `$range` | `array` |  | the pack's range from `rpResolveRange()` - tz is used |
| `$grouping` | `string` |  | 'day', 'week' or 'month' (as `rpTimeBuckets()` settled it) |
| `$keys` | `array` |  | the buckets from `rpTimeBuckets()`, key => label |

**Returns:** `array`

## Calls

[rpBucketKey()](/code/functions/rpBucketKey.md)

## Called from

- [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) - in [rpTicketsTrend()](/code/functions/rpTicketsTrend.md)

