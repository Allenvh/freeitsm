# dbVerifyPreview()

Works out, without changing anything, what a Database Verification run would do - tables to create, columns to add, drops whose target is present, and data repairs with rows to rewrite.

```php
function dbVerifyPreview(PDO $conn, array $schema, string $dbName): array
```

Defined in [includes/db_verify_preview.php](/code/files/includes/db_verify_preview.php.md), line 176.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$schema` | `array` |  | the db_verify table => [column => definition] map |
| `$dbName` | `string` |  | the database (schema) name being verified |

**Returns:** array  the four lists plus a summary of counts, and read_only => true

## Calls

[dbPreviewColumnExists()](/code/functions/dbPreviewColumnExists.md), [dbPreviewIndexExists()](/code/functions/dbPreviewIndexExists.md), [dbPreviewRepairs()](/code/functions/dbPreviewRepairs.md), [dbPreviewTableExists()](/code/functions/dbPreviewTableExists.md)

## Called from

- [api/system/db_verify_preview.php](/code/files/api/system/db_verify_preview.php.md)

