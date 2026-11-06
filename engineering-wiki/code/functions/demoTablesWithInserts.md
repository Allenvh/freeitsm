# demoTablesWithInserts()

The tables a parsed demo module actually INSERTS into.

A table listed only with `_skip_insert` records is excluded: those records
match an existing row to resolve a reference (the admin analyst, the CMDB
icons) and the importer never creates or removes them. Getting this wrong in
the clearing direction is what makes an importer destructive, so it is
deliberately the narrow reading.

```php
function demoTablesWithInserts(array $demoData): array
```

Defined in [includes/demo_data.php](/code/files/includes/demo_data.php.md), line 82.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$demoData` | `array` |  | a decoded demo-data JSON file: tier1..tier5 => [table => [record, ...]] |

**Returns:** `array`

## Called from

- [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md)
- [includes/demo_data.php](/code/files/includes/demo_data.php.md) - in [demoModuleTables()](/code/functions/demoModuleTables.md)

