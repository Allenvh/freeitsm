# warbotToolsFor()

The tools this analyst may actually use, in the shape aiProviderChatTools wants. A capability they lack means the tool is not OFFERED at all, rather than offered and then refused — a model told about a tool it cannot use will keep reaching for it and narrate the failure to the room.

```php
function warbotToolsFor(PDO $conn, int $analystId): array
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 353.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[warbotToolAllowed()](/code/functions/warbotToolAllowed.md), [warbotTools()](/code/functions/warbotTools.md)

## Called from

- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotAnswer()](/code/functions/warbotAnswer.md)

