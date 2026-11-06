# autoLayoutProcessMapper()

Auto-layout for the process-mapper module: writes x/y/width/height onto each step in `$demoData`['tier2']['process_steps'] using a left-to-right layered placement. Each process is laid out independently. Edges that point "backwards" in JSON authoring order are treated as cycle-closing back-edges and ignored for ranking.

```php
function autoLayoutProcessMapper(array &$demoData)
```

Defined in [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md), line 102.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$demoData` | `array` |  | the whole demo data set, by reference; tier2 process_steps get x, y, width and height |

## Calls

[processMapperDefaultSize()](/code/functions/processMapperDefaultSize.md)

## Called from

- [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md)

