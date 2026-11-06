# warbotTryCommand()

Run a slash command. Returns null if the text is not one. 🔑 NO MODEL INVOLVED — this is the path that still works during the outage.

```php
function warbotTryCommand(PDO $conn, int $analystId, string $body): ?string
```

Defined in [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md), line 119.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `string` |  | the message text; a command is a leading "/name" with any argument after it |

**Returns:** `?string`

## Calls

[warbotCommands()](/code/functions/warbotCommands.md), [warbotHelpText()](/code/functions/warbotHelpText.md), [warbotRunTool()](/code/functions/warbotRunTool.md)

## Called from

- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotAnswer()](/code/functions/warbotAnswer.md)

