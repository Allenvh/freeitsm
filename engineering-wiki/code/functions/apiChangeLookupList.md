# apiChangeLookupList()

Reference lookups

```php
function apiChangeLookupList(PDO $conn, string $table, bool $hasIsClosed = false): void
```

Defined in [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md), line 501.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the change lookup table to list (change_statuses, change_types ...) - fixed by the caller, never input |
| `$hasIsClosed` | `bool` | `false` | true to include the table's is_closed column (change_statuses only) |

**Returns:** `void`

## Calls

[apiRespond()](/code/functions/apiRespond.md)

## Called from

- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangeStatusesList()](/code/functions/apiChangeStatusesList.md), [apiChangeTypesList()](/code/functions/apiChangeTypesList.md), [apiChangePrioritiesList()](/code/functions/apiChangePrioritiesList.md), [apiChangeImpactsList()](/code/functions/apiChangeImpactsList.md)

