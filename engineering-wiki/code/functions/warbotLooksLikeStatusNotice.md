# warbotLooksLikeStatusNotice()

Is this one of Warbot's own "I am degraded" notices?

Matched on the sentinel sentence `warbotNoBrain()` always ends with, which is a
string WE generate rather than one we hope the model produced — so this is a
check against our own output, not a guess about somebody else's. A translated
locale would need its own sentinel; until then a missed match only means the
notice is truncated to 200 characters rather than dropped, which is already
enough to stop it being copied wholesale.

```php
function warbotLooksLikeStatusNotice(string $body): bool
```

Defined in [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md), line 308.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `string` |  | an earlier Warbot message's text |

**Returns:** `bool`

## Called from

- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotAnswer()](/code/functions/warbotAnswer.md)

