# analystReferenceRegistry()

Every column that refers to an analyst, and what kind of reference it is. Each row: [table, column, kind] or [table, column, kind, extraWhere] where extraWhere narrows a polymorphic or partly-live column (trusted SQL, no placeholders). See the file header for the kinds.

```php
function analystReferenceRegistry(): array
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 56.

**Returns:** array<int,array{0:string,1:string,2:string,3?:string}>

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 57 | `analystReferenceRegistry` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 58 | `analystReferenceRegistry` | read [change_statuses](/code/tables/change_statuses.md) |  |
| 59 | `analystReferenceRegistry` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 60 | `analystReferenceRegistry` | read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 114 | `analystReferenceRegistry` | read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 116 | `analystReferenceRegistry` | read [task_statuses](/code/tables/task_statuses.md) |  |
| 125 | `analystReferenceRegistry` | read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 127 | `analystReferenceRegistry` | read [task_statuses](/code/tables/task_statuses.md) |  |

## Called from

- [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) - in [analystLiveGroups()](/code/functions/analystLiveGroups.md), [analystFootprint()](/code/functions/analystFootprint.md), [analystRemovePersonal()](/code/functions/analystRemovePersonal.md)

