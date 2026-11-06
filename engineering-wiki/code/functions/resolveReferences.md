# resolveReferences()

Helper: resolve `@table`.ref references to real IDs

```php
function resolveReferences($record, $idMap)
```

Defined in [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$record` |  |  | one demo record, column => value; a value starting @ is a reference like `@table`.ref |
| `$idMap` |  |  | the ids inserted so far, keyed 'table.ref' |

## Called from

- [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md)

