# dsyncGetRun()

Read a run back.

```php
function dsyncGetRun(PDO $conn, int $runId): array
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 1041.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$runId` | `int` |  | directory_sync_runs.id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1043 | `dsyncGetRun` | read [directory_sync_runs](/code/tables/directory_sync_runs.md) |  |

## Called from

- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)

