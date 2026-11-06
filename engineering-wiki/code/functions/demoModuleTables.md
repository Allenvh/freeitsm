# demoModuleTables()

As above, but reads the module's JSON from disk. Returns [] if unreadable.

```php
function demoModuleTables(string $module): array
```

Defined in [includes/demo_data.php](/code/files/includes/demo_data.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$module` | `string` |  | a DEMO_MODULES key, e.g. 'tickets' |

**Returns:** `array`

## Calls

[demoDataPath()](/code/functions/demoDataPath.md), [demoTablesWithInserts()](/code/functions/demoTablesWithInserts.md)

## Called from

- [includes/demo_data.php](/code/files/includes/demo_data.php.md) - in [demoRowCount()](/code/functions/demoRowCount.md), [demoBlockingModules()](/code/functions/demoBlockingModules.md)

