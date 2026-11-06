# demoRowCount()

How many demo rows a module currently has in the database.

Counts only rows marked is_demo = 1, so real data is never mistaken for
demo data. A missing table or column counts as zero rather than throwing —
a module whose feature was never set up simply has no demo rows.

```php
function demoRowCount(PDO $conn, string $module): int
```

Defined in [includes/demo_data.php](/code/files/includes/demo_data.php.md), line 119.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$module` | `string` |  | a DEMO_MODULES key, e.g. 'tickets' |

**Returns:** `int`

## Calls

[demoModuleTables()](/code/functions/demoModuleTables.md)

## Called from

- [api/system/check_demo_core.php](/code/files/api/system/check_demo_core.php.md)

