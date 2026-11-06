# awcAdoptExistingCategory()

Adopt whatever category this kind of entry is already filed under.

This is what makes renaming safe for an install that renamed the category
BEFORE ids were recorded - there is no migration to run and nothing for
anybody to do; the entries themselves say where they live.

Does nothing once an id is recorded, and nothing if there are no entries.

```php
function awcAdoptExistingCategory(PDO $conn, string $source): void
```

Defined in [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md), line 251.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$source` | `string` |  | the entry kind ('asset_warranty' or 'asset_lease') whose existing entries are checked |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 256 | `awcAdoptExistingCategory` | read [calendar_categories](/code/tables/calendar_categories.md) |  |
| 262 | `awcAdoptExistingCategory` | read [calendar_events](/code/tables/calendar_events.md) |  |

## Calls

[awcGetSetting()](/code/functions/awcGetSetting.md), [awcSetSetting()](/code/functions/awcSetSetting.md)

## Called from

- [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md) - in [awcSyncKind()](/code/functions/awcSyncKind.md)
- [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md) - in [domainSyncCalendarKind()](/code/functions/domainSyncCalendarKind.md)
- [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md) - in [projectSyncCalendarKind()](/code/functions/projectSyncCalendarKind.md)

