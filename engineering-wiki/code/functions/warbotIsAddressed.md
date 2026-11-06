# warbotIsAddressed()

Does this message address Warbot? Either "@Warbot …" anywhere in it, or a leading slash command.

```php
function warbotIsAddressed(string $body): bool
```

Defined in [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md), line 42.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `string` |  | the chat message text as posted |

**Returns:** `bool`

## Calls

[warbotCommands()](/code/functions/warbotCommands.md)

## Called from

- [api/war-room/warbot.php](/code/files/api/war-room/warbot.php.md)

