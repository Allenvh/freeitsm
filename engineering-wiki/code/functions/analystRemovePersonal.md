# analystRemovePersonal()

Remove everything of kind personal and secret for this analyst.

```php
function analystRemovePersonal(PDO $conn, int $analystId): int
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 563.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | analysts.id |

**Returns:** int rows removed

## Calls

[analystLcColumnExists()](/code/functions/analystLcColumnExists.md), [analystReferenceRegistry()](/code/functions/analystReferenceRegistry.md)

## Called from

- [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) - in [analystAnonymise()](/code/functions/analystAnonymise.md), [analystDelete()](/code/functions/analystDelete.md)

