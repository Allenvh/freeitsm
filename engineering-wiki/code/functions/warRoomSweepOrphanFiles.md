# warRoomSweepOrphanFiles()

Delete attachment files with no row left pointing at them.

Rate-limited to once an hour by a timestamp in system_settings, because this
is the only thing in the module that lists a directory and it hangs off the
send path. An hour is generous: an orphan is wasted disk, not a live problem.

Still no cron. A file that outlives its row by up to an hour is fine; a module
that needs a scheduled task set up before it cleans up after itself is not.

```php
function warRoomSweepOrphanFiles(PDO $conn): void
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 1300.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1304 | `warRoomSweepOrphanFiles` | read [system_settings](/code/tables/system_settings.md) |  |
| 1310 | `warRoomSweepOrphanFiles` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 1318 | `warRoomSweepOrphanFiles` | read [warroom_attachments](/code/tables/warroom_attachments.md) |  |

## Called from

- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomPrune()](/code/functions/warRoomPrune.md)

