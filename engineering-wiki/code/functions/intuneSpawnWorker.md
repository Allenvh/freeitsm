# intuneSpawnWorker()

Spawn the CLI worker so it survives the web request. Returns true on success.

```php
function intuneSpawnWorker(int $jobId, ?string $phpExeOverride): bool
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 573.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$jobId` | `int` |  | the intune_sync_jobs id passed to the worker on its command line |
| `$phpExeOverride` | `?string` |  | a PHP CLI path from the settings, or null to search - see `intuneFindPhpExe()` |

**Returns:** `bool`

## Calls

[intuneSpawnGenericWorker()](/code/functions/intuneSpawnGenericWorker.md)

## Called from

- [api/intune/sync.php](/code/files/api/intune/sync.php.md)

