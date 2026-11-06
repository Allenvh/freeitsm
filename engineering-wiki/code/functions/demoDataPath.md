# demoDataPath()

Returns the path of a module's demo data file, database/demo-data/{module}.json.

```php
function demoDataPath(string $module): string
```

Defined in [includes/demo_data.php](/code/files/includes/demo_data.php.md), line 67.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$module` | `string` |  | a DEMO_MODULES key, e.g. 'tickets' (trusted - put straight into the path) |

**Returns:** `string`

## Called from

- [includes/demo_data.php](/code/files/includes/demo_data.php.md) - in [demoModuleTables()](/code/functions/demoModuleTables.md)

