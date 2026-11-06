# csAdoptOrphans()

🔴 NAME THE CONNECTION ON ROWS THAT ONLY IMPLY IT.

An enrolment or event made while the install had a single connection may not
name it, and works only because "the only connection" is the fallback. The
moment a second connection is added that fallback stops answering and those
analysts would silently stop syncing - so, while there is still exactly one,
write it onto them. Run before anything that adds, resets or removes a
connection.

```php
function csAdoptOrphans(PDO $conn): void
```

Defined in [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md), line 117.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 121 | `csAdoptOrphans` | **update** [calendar_enrolments](/code/tables/calendar_enrolments.md) (`connection_id`) |  |
| 122 | `csAdoptOrphans` | **update** [calendar_sync_events](/code/tables/calendar_sync_events.md) (`connection_id`) |  |

## Calls

[calendarSyncConnectionIds()](/code/functions/calendarSyncConnectionIds.md)

## Called from

- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)

