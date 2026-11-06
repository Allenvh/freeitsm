# demoDuplicateExplanation()

Explain a unique-key collision during an import in terms of its actual cause.

On an installation whose demo data predates the is_demo mark, the importer
cannot recognise the old sample rows, so it does not remove them, and then
collides with them: `Duplicate entry 'jsmith' for key analysts.uq_...`. That
message is true and useless — it names a row the administrator never created
and says nothing about what to do. It is also the likeliest failure on
exactly the installations most at risk, so it is worth spelling out.

```php
function demoDuplicateExplanation(
    PDO $conn,
    string $module,
    string $table,
    string $dbMessage
): string
```

Defined in [includes/demo_data.php](/code/files/includes/demo_data.php.md), line 189.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$module` | `string` |  | the DEMO_MODULES key being imported |
| `$table` | `string` |  | the table the insert collided in |
| `$dbMessage` | `string` |  | the database error text; the "Duplicate entry '...'" value is quoted from it |

**Returns:** `string`

## Calls

[demoHasUntaggedImport()](/code/functions/demoHasUntaggedImport.md)

## Called from

- [api/system/import_demo_data.php](/code/files/api/system/import_demo_data.php.md)

