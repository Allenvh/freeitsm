# analystLcCount()

How many rows of one registry entry refer to this analyst.

```php
function analystLcCount(PDO $conn, array $ref, int $analystId): int
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 345.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ref` | `array` |  | one `analystReferenceRegistry()` row |
| `$analystId` | `int` |  | analysts.id |

**Returns:** `int`

## Calls

[analystLcColumnExists()](/code/functions/analystLcColumnExists.md)

## Called from

- [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md) - in [analystFootprint()](/code/functions/analystFootprint.md)

