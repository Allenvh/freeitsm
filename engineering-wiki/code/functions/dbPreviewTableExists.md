# dbPreviewTableExists()

Does this table exist?

```php
function dbPreviewTableExists(PDO $conn, string $dbName, string $table): bool
```

Defined in [includes/db_verify_preview.php](/code/files/includes/db_verify_preview.php.md), line 78.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$dbName` | `string` |  | the database (schema) name to look in |
| `$table` | `string` |  | the table name |

**Returns:** `bool`

## Called from

- [includes/db_verify_preview.php](/code/files/includes/db_verify_preview.php.md) - in [dbPreviewRepairs()](/code/functions/dbPreviewRepairs.md), [dbVerifyPreview()](/code/functions/dbVerifyPreview.md)

