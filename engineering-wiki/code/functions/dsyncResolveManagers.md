# dsyncResolveManagers()

Second pass: turn each person's manager DN into a manager_id.

Separate from the main loop because `manager` is a DN, and the person it names
is very often somebody the same run has only just created — resolving inline
would work for whoever happened to be processed later and fail for everybody
else, which is the worst kind of half-working.

A manager OUTSIDE the synced scope is not an error: plenty of organisations
have people reporting to someone in an OU nobody chose to import. Those are
left unset and counted, rather than logged forty times.

```php
function dsyncResolveManagers(
    PDO $conn,
    array $people,
    array $dnToUserId,
    int $runId,
    bool $preview
): void
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 975.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$people` | `array` |  | every mapped person this run found, as `dsyncMapPerson()` returns them |
| `$dnToUserId` | `array` |  | lower-cased DN => users.id for everyone matched or created this run |
| `$runId` | `int` |  | directory_sync_runs.id to log against |
| `$preview` | `bool` |  | true to work it out without writing manager_id |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1001 | `dsyncResolveManagers` | **update** [users](/code/tables/users.md) (`manager_id`) |  |

## Calls

[dsyncLogEntry()](/code/functions/dsyncLogEntry.md), [userManagerIsSafe()](/code/functions/userManagerIsSafe.md)

## Called from

- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)

