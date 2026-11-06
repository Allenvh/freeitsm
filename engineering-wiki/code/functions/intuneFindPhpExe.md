# intuneFindPhpExe()

Locate a usable PHP CLI binary so the web request can spawn a worker. Tries: explicit setting → PHP_BINARY's directory → known WAMP layout → 'php' from PATH. Returns the path or throws if nothing works.

```php
function intuneFindPhpExe(?string $override): string
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 544.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$override` | `?string` |  | a PHP CLI path from the intune_php_exe setting, tried first; null or '' to search |

**Returns:** `string`

## Called from

- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneSpawnGenericWorker()](/code/functions/intuneSpawnGenericWorker.md)

