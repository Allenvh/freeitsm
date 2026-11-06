# intuneSpawnAppWorker()

Spawn the CLI app-sync worker.

```php
function intuneSpawnAppWorker(int $jobId, ?string $phpExeOverride): bool
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 934.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$jobId` | `int` |  | the intune_app_sync_jobs id passed to the worker on its command line |
| `$phpExeOverride` | `?string` |  | a PHP CLI path from the settings, or null to search - see `intuneFindPhpExe()` |

**Returns:** `bool`

## Calls

[intuneSpawnGenericWorker()](/code/functions/intuneSpawnGenericWorker.md)

## Called from

- [api/intune/create_app_sync_job.php](/code/files/api/intune/create_app_sync_job.php.md)

