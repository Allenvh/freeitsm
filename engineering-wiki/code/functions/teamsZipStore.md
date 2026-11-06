# teamsZipStore()

A plain zip archive with every entry stored (no compression). Written by hand so the package does not depend on the PHP zip extension, which this image lacks. Three small files gain nothing from compression, and stored entries are the simplest form every zip reader, Teams included, accepts.

```php
function teamsZipStore(array $files): string
```

Defined in [includes/messaging/TeamsPackage.php](/code/files/includes/messaging/TeamsPackage.php.md), line 90.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$files` | `array` |  | entry name => bytes |

**Returns:** `string`

## Called from

- [includes/messaging/TeamsPackage.php](/code/files/includes/messaging/TeamsPackage.php.md) - in [teamsPackageBuild()](/code/functions/teamsPackageBuild.md)

