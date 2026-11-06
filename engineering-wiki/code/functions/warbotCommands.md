# warbotCommands()

The commands that work with NO internet.

Deliberately a small, memorable set rather than a mirror of every tool: this is
the fallback somebody reaches for while the building is on fire, so it has to
be typeable from memory. Each maps to a tool handler and a fixed argument shape,
because parsing natural language is precisely the part that needs the model.

```php
function warbotCommands(): array
```

Defined in [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md), line 56.

**Returns:** `array`

## Called from

- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotIsAddressed()](/code/functions/warbotIsAddressed.md), [warbotHelpText()](/code/functions/warbotHelpText.md), [warbotTryCommand()](/code/functions/warbotTryCommand.md)

