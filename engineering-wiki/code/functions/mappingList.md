# mappingList()

A lookup list, tolerant of a table that is not there on every install.

```php
function mappingList(PDO $conn, string $sql): array
```

Defined in [api/integrations/get_mapping.php](/code/files/api/integrations/get_mapping.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$sql` | `string` |  | a fixed SELECT written in this file - never built from user input |

**Returns:** `array`

## Called from

- [api/integrations/get_mapping.php](/code/files/api/integrations/get_mapping.php.md)

