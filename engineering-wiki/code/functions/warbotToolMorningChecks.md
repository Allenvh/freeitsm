# warbotToolMorningChecks()

morning_checks: the morning check results for a date (today in UTC by default), listing the ones that are not clear, or the first few when all are.

```php
function warbotToolMorningChecks(PDO $conn, array $args, int $analystId): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 849.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the model's arguments - untrusted: date ('Y-m-d'; anything else means today in UTC) |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

*Nothing calls this function by name.*

