# leaseGoneBackSql()

SQL: the asset (alias `$a`) has GONE BACK - it is on a lease, and none of its lease rows is still out (every one is Returned, Bought out, ...).

TRAP: a returned asset keeps lease_expiry as the record of what happened (see
the header), so anything that alerts on lease_expiry must skip these, or a
machine handed back in March is "due back" for ever. Call only when `leaseReady()`.

```php
function leaseGoneBackSql(string $a = 'a'): string
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 144.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` | `string` | `'a'` | the alias the calling query gives the assets table |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 146 | `leaseGoneBackSql` | read [lease_assets](/code/tables/lease_assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |

## Calls

[leaseActiveSql()](/code/functions/leaseActiveSql.md)

## Called from

- [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md) - in [awcSyncKind()](/code/functions/awcSyncKind.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

