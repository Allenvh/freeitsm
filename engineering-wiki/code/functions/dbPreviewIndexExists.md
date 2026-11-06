# dbPreviewIndexExists()

Does this index exist?

```php
function dbPreviewIndexExists(PDO $conn, string $dbName, string $table, string $index): bool
```

Defined in [includes/db_verify_preview.php](/code/files/includes/db_verify_preview.php.md), line 106.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$dbName` | `string` |  | the database (schema) name to look in |
| `$table` | `string` |  | the table name |
| `$index` | `string` |  | the index name, as information_schema.statistics.index_name |

**Returns:** `bool`

## Called from

- [includes/db_verify_preview.php](/code/files/includes/db_verify_preview.php.md) - in [dbVerifyPreview()](/code/functions/dbVerifyPreview.md)

