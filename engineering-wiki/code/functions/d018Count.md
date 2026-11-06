# d018Count()

Run one COUNT(*) and return the number, or the database's own error text.

```php
function d018Count(PDO $conn, string $sql, array $params = [])
```

Defined in [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md), line 99.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | the open database connection |
| `$sql` | `string` |  | the query, with ? placeholders |
| `$params` | `array` | `[]` | values for the placeholders, in order |

**Returns:** int|string the count, or "ERROR: ..." when the query fails

## Called from

- [api/system/debug-tools/D018_ticket_folder_counts.php](/code/files/api/system/debug-tools/D018_ticket_folder_counts.php.md)

