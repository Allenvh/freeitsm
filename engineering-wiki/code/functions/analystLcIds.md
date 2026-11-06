# analystLcIds()

The ids of the rows one registry entry matches (for the live groups).

```php
function analystLcIds(PDO $conn, array $ref, int $analystId): array
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 397.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ref` | `array` |  | one `analystReferenceRegistry()` row |
| `$analystId` | `int` |  | analysts.id |

**Returns:** int[]

## Calls

[analystLcColumnExists()](/code/functions/analystLcColumnExists.md)

## Called from

- [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) - in [analystLiveGroups()](/code/functions/analystLiveGroups.md)

