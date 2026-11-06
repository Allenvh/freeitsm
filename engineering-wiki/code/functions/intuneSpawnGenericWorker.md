# intuneSpawnGenericWorker()

Generic spawn helper used by both the device sync and the app sync workers.

```php
function intuneSpawnGenericWorker(
    string $workerScriptName,
    int $jobId,
    ?string $phpExeOverride
): bool
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 584.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$workerScriptName` | `string` |  | the worker's file name inside scripts/, e.g. 'intune_worker.php' |
| `$jobId` | `int` |  | the job id passed to the worker on its command line |
| `$phpExeOverride` | `?string` |  | a PHP CLI path from the settings, or null to search - see `intuneFindPhpExe()` |

**Returns:** `bool`

## Calls

[intuneFindPhpExe()](/code/functions/intuneFindPhpExe.md)

## Called from

- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneSpawnWorker()](/code/functions/intuneSpawnWorker.md), [intuneSpawnAppWorker()](/code/functions/intuneSpawnAppWorker.md)

