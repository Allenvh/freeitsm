# rpBucketKey()

The bucket a date falls in: the Monday of its week, its month (Y-m) or its day.

```php
function rpBucketKey(DateTimeImmutable $d, string $grouping): string
```

Defined in [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md), line 150.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` | `DateTimeImmutable` |  | the date, already in the viewer's timezone |
| `$grouping` | `string` |  | 'day', 'week' or 'month' |

**Returns:** `string`

## Called from

- [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) - in [rpTimeBuckets()](/code/functions/rpTimeBuckets.md), [rpBucketCount()](/code/functions/rpBucketCount.md)

