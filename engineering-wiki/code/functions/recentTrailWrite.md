# recentTrailWrite()

The write itself, with no opinion about what kind of request it is.

⚠️ TWO CALLERS, AND THE SECOND ONE IS NOT OPTIONAL. `entityVisit()` covers a
record whose page the SERVER rendered — a deep link from search, a
notification, a preview, or a page that is a record in its own right such as
contracts/view.php.

🔴 BUT THAT IS THE MINORITY OF HOW RECORDS ARE ACTUALLY OPENED. Tickets,
tasks, assets, problems, changes and knowledge are all single screens whose
lists open a record IN PLACE, without a page load and (for most of them)
without even touching the URL. Server-side hooks alone would therefore have
recorded only the times you ARRIVED at a module from somewhere else, and
missed every ticket you opened while you were in it — which is precisely the
work the trail exists to lead you back to. api/system/recent_trail_visit.php
is the other half, and it calls this.

```php
function recentTrailWrite(string $type, int $id, ?PDO $conn = null): void
```

Defined in [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), line 158.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | the record type, a RECENT_TRAIL_MODULES key; anything else is ignored |
| `$id` | `int` |  | the record's id; 0 or less is ignored |
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 181 | `recentTrailWrite` | read [analyst_recent_trail](/code/tables/analyst_recent_trail.md) |  |
| 192 | `recentTrailWrite` | **update** [analyst_recent_trail](/code/tables/analyst_recent_trail.md) (`visited_datetime`) |  |
| 203 | `recentTrailWrite` | **insert** [analyst_recent_trail](/code/tables/analyst_recent_trail.md) (`analyst_id`, `entity_type`, `entity_id`, `visited_datetime`) |  |

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md), [recentTrailPrune()](/code/functions/recentTrailPrune.md)

## Called from

- [api/system/recent_trail_visit.php](/code/files/api/system/recent_trail_visit.php.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [entityVisit()](/code/functions/entityVisit.md)

