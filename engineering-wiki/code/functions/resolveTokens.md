# resolveTokens()

Helper: resolve special tokens

```php
function resolveTokens($record, $conn)
```

Defined in [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md), line 55.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$record` |  |  | one demo record, column => value; tokens such as __NOW__ or __RELATIVE_DATE:-3d__ are replaced |
| `$conn` |  |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

## Calls

[generateDemoTicketNumber()](/code/functions/generateDemoTicketNumber.md)

## Called from

- [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md)

