# d018Columns()

The columns of one table, or null when the table does not exist.

```php
function d018Columns(PDO $conn, string $table): ?array
```

Defined in [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md), line 84.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | the open database connection |
| `$table` | `string` |  | the table name (trusted - a literal from this file) |

**Returns:** string[]|null column names, or null for a missing table

## Called from

- [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md)

