# warbotToolProjectRaid()

project_raid: one project's RAID log via `projectAssistRaid()`, scoped to the projects the asker may see.

```php
function warbotToolProjectRaid(PDO $conn, array $args, int $analystId): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 1051.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the model's arguments - untrusted: project, type (risk, issue...), status (open/closed/all) |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Calls

[projectAssistRaid()](/code/functions/projectAssistRaid.md), [warbotProjectAnswer()](/code/functions/warbotProjectAnswer.md), [warbotProjectScope()](/code/functions/warbotProjectScope.md)

*Nothing calls this function by name.*

