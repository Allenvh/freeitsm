# domainSyncCalendarKind()

One kind: adopt its category, clear its entries, and - if its surface says calendar - draw one all-day entry per domain with a date in `$column`. The column name is interpolated, never bound; it comes only from this file.

```php
function domainSyncCalendarKind(
    PDO $conn,
    string $source,
    string $surface,
    string $categoryName,
    string $colour,
    string $column,
    string $prefix,
    string $description,
    string $live
): int
```

Defined in [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md), line 78.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$source` | `string` |  | the calendar_events.source marker for this kind, 'domain_expiry' or 'domain_cert' |
| `$surface` | `string` |  | that kind's surface setting - off \| dashboard \| calendar \| both; only the last two draw |
| `$categoryName` | `string` |  | the calendar category to create if none is remembered for this source |
| `$colour` | `string` |  | hex colour for a newly created category, e.g. '#4d7c0f' |
| `$column` | `string` |  | the domains date column to draw from - interpolated into SQL, so only literals from here |
| `$prefix` | `string` |  | put before the domain name in each entry's title, e.g. 'Domain expires: ' |
| `$description` | `string` |  | the description written on every entry |
| `$live` | `string` |  | an SQL condition on domain_statuses alias s that a domain must meet to be drawn |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 82 | `domainSyncCalendarKind` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |
| 87 | `domainSyncCalendarKind` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 94 | `domainSyncCalendarKind` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source`) |  |

## Calls

[awcAdoptExistingCategory()](/code/functions/awcAdoptExistingCategory.md), [awcEnsureCategory()](/code/functions/awcEnsureCategory.md)

## Called from

- [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md) - in [domainSyncExpiryCalendar()](/code/functions/domainSyncExpiryCalendar.md)

