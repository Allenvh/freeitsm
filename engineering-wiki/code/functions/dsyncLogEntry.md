# dsyncLogEntry()

Record one thing a run did to one person.

```php
function dsyncLogEntry(
    PDO $conn,
    int $runId,
    string $action,
    ?int $userId,
    array $p,
    string $detail = ''
): void
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 518.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$runId` | `int` |  | directory_sync_runs.id |
| `$action` | `string` |  | what happened: 'create', 'update', 'adopt', 'unchanged', 'deactivate', 'conflict', 'skip' or 'error' |
| `$userId` | `?int` |  | users.id it happened to; null when there is no record (yet) |
| `$p` | `array` |  | the person - only username and name are read |
| `$detail` | `string` | `''` | plain-English explanation, cut to 1000 characters |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 522 | `dsyncLogEntry` | **insert** [directory_sync_entries](/code/tables/directory_sync_entries.md) (`run_id`, `action`, `user_id`, `directory_username`, `display_name`, `detail`, `created_datetime`) |  |

## Called from

- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md), [dsyncHandleMissing()](/code/functions/dsyncHandleMissing.md), [dsyncResolveManagers()](/code/functions/dsyncResolveManagers.md)

