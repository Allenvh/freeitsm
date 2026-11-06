# warbotNoBrain()

What Warbot says when the brain is unreachable but the hands still work.

```php
function warbotNoBrain(string $why): string
```

Defined in [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md), line 319.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$why` | `string` |  | the reason, worded to start a sentence, e.g. 'no AI provider is configured' |

**Returns:** `string`

## Calls

[warbotHelpText()](/code/functions/warbotHelpText.md)

## Called from

- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotAnswer()](/code/functions/warbotAnswer.md)

