# storagePersistenceStatus()

Assess one directory. Returns 'persisted' | 'at_risk' | 'missing' | 'unknown'.

```php
function storagePersistenceStatus(string $dir, ?int $rootDev = null): string
```

Defined in [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md), line 200.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dir` | `string` |  | the absolute directory to check |
| `$rootDev` | `?int` | `null` | the device number of '/', when the caller already has it; null stats '/' here |

**Returns:** `string`

## Called from

- [api/system/docker_tls.php](/code/files/api/system/docker_tls.php.md)
- [includes/docker_tls.php](/code/files/includes/docker_tls.php.md) - in [dockerTlsState()](/code/functions/dockerTlsState.md)
- [includes/storage_persistence.php](/code/files/includes/storage_persistence.php.md) - in [storagePersistenceReport()](/code/functions/storagePersistenceReport.md)

