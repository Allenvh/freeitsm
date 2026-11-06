# featureBingoEvaluate()

Every card with its state: 'lit' (configured), 'unlit', or 'dismissed' (Not for us - out of the score, whatever its check says). Plus the totals.

```php
function featureBingoEvaluate(PDO $conn): array
```

Defined in [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md), line 240.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[featureBingoCards()](/code/functions/featureBingoCards.md), [featureBingoDismissed()](/code/functions/featureBingoDismissed.md), [featureBingoRunCheck()](/code/functions/featureBingoRunCheck.md)

## Called from

- [api/system/feature_bingo.php](/code/files/api/system/feature_bingo.php.md)

