# awcSyncKind()

Clear and rebuild one kind of auto-generated asset date entry.

`$ready` is the caller's answer to "does the column exist yet" - passed in
rather than checked here so the two kinds cannot accidentally gate each
other, which is how a missing lease column would otherwise have taken the
warranty entries down with it.

Clearing happens even when the kind is switched OFF, and even when the
column is missing: that is what makes turning it off actually remove the
entries instead of leaving the last set behind for ever.

```php
function awcSyncKind(
    PDO $conn,
    string $source,
    string $surface,
    string $column,
    string $categoryName,
    string $categoryColour,
    string $titlePrefix,
    string $description,
    bool $ready
): int
```

Defined in [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md), line 116.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$source` | `string` |  | the calendar_events.source tag for this kind - 'asset_warranty' or 'asset_lease' |
| `$surface` | `string` |  | where this kind is shown - off, dashboard, calendar or both; only calendar/both write entries |
| `$column` | `string` |  | the assets date column to read - warranty_expiry or lease_expiry (interpolated, never user input) |
| `$categoryName` | `string` |  | the calendar category's default name, used when one has to be found or made |
| `$categoryColour` | `string` |  | the colour (#rrggbb) for a newly created category only |
| `$titlePrefix` | `string` |  | put before the hostname in each entry's title, e.g. 'Warranty expiry: ' |
| `$description` | `string` |  | the description stored on every entry of this kind |
| `$ready` | `bool` |  | whether `$column` exists yet; false clears the entries and writes none |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 132 | `awcSyncKind` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |
| 151 | `awcSyncKind` | read [assets](/code/tables/assets.md) |  |
| 161 | `awcSyncKind` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source`) |  |

## Calls

[awcAdoptExistingCategory()](/code/functions/awcAdoptExistingCategory.md), [awcEnsureCategory()](/code/functions/awcEnsureCategory.md), [leaseGoneBackSql()](/code/functions/leaseGoneBackSql.md), [leaseReady()](/code/functions/leaseReady.md)

## Called from

- [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md) - in [syncAssetWarrantyCalendar()](/code/functions/syncAssetWarrantyCalendar.md)

