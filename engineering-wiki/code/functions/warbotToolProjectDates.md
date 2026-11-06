# warbotToolProjectDates()

project_dates: one project's key dates via `projectAssistDates()`, scoped to the projects the asker may see.

```php
function warbotToolProjectDates(PDO $conn, array $args, int $analystId): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 1073.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the model's arguments - untrusted: days (how far ahead, default 14, max 90) |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[projectAssistDates()](/code/functions/projectAssistDates.md), [warbotProjectAnswer()](/code/functions/warbotProjectAnswer.md), [warbotProjectScope()](/code/functions/warbotProjectScope.md)

*Nothing calls this function by name.*

