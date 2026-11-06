# warbotRunTool()

Run one tool. NEVER throws — a failure is returned as words, because "the CMDB lookup failed" is something the model can usefully tell the room, whereas an exception here would lose the whole answer.

```php
function warbotRunTool(PDO $conn, int $analystId, string $name, array $args): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 371.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$name` | `string` |  | the tool name the model asked for, a key of `warbotTools()`; unknown names are refused |
| `$args` | `array` |  | the model's arguments for it, decoded JSON - untrusted |

**Returns:** `string`

## Calls

[warbotToolAllowed()](/code/functions/warbotToolAllowed.md), [warbotTools()](/code/functions/warbotTools.md)

## Called from

- [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md)
- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotTryCommand()](/code/functions/warbotTryCommand.md), [warbotAnswer()](/code/functions/warbotAnswer.md)

