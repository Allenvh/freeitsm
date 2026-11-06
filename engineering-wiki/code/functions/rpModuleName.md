# rpModuleName()

A module's display name, for "You do not have access to X".

```php
function rpModuleName(string $key): string
```

Defined in [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md), line 404.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the module key, e.g. 'contracts' |

**Returns:** `string`

## Calls

[getModuleRegistry()](/code/functions/getModuleRegistry.md)

## Called from

- [includes/report_packs/blocks.php](/code/files/includes/report_packs/blocks.php.md) - in [rpBlockData()](/code/functions/rpBlockData.md), [rpCatalogue()](/code/functions/rpCatalogue.md)

