# projectSyncCalendarKind()

One kind: adopt its category, clear its entries, and - when on - draw one all-day entry per row.

```php
function projectSyncCalendarKind(
    PDO $conn,
    string $source,
    bool $on,
    string $sql,
    callable $title,
    callable $description
): int
```

Defined in [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md), line 105.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$source` | `string` |  | the calendar_events.source this kind owns, e.g. 'project_milestone' |
| `$on` | `bool` |  | whether this kind is switched on; false only clears its entries |
| `$sql` | `string` |  | a trusted SELECT giving one row per entry, with on_date and whatever `$title` and `$description` read |
| `$title` | `callable` |  | fn(row): string - the entry's title (cut to 255 characters) |
| `$description` | `callable` |  | fn(row): string - the entry's description |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 108 | `projectSyncCalendarKind` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |
| 113 | `projectSyncCalendarKind` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source`) |  |

## Calls

[awcAdoptExistingCategory()](/code/functions/awcAdoptExistingCategory.md), [awcEnsureCategory()](/code/functions/awcEnsureCategory.md)

## Called from

- [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md) - in [projectSyncCalendar()](/code/functions/projectSyncCalendar.md)

