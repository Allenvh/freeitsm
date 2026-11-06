# intuneRunSync()

Run the full sync for a given job id. Updates intune_sync_jobs as it goes so the UI progress bar can poll. Designed for CLI worker use.

```php
function intuneRunSync(PDO $conn, int $jobId): void
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 443.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$jobId` | `int` |  | the intune_sync_jobs id whose progress this updates |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 455 | `intuneRunSync` | **update** [intune_sync_jobs](/code/tables/intune_sync_jobs.md) (`total`, `processed`, `status`, `finished_datetime`, `message`) |  |

## Calls

[intuneGetSettings()](/code/functions/intuneGetSettings.md), [intuneGetToken()](/code/functions/intuneGetToken.md), [intuneGraphGet()](/code/functions/intuneGraphGet.md), [intuneLinkDevicesToAssets()](/code/functions/intuneLinkDevicesToAssets.md), [intuneSyncDisks()](/code/functions/intuneSyncDisks.md), [intuneUpsertDevice()](/code/functions/intuneUpsertDevice.md)

*Nothing calls this function by name.*

