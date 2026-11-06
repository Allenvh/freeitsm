# featureBingoDismissed()

The ids an administrator has marked "Not for us".

```php
function featureBingoDismissed(PDO $conn): array
```

Defined in [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md), line 227.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 230 | `featureBingoDismissed` | read [feature_bingo_dismissed](/code/tables/feature_bingo_dismissed.md) |  |

## Called from

- [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md) - in [featureBingoEvaluate()](/code/functions/featureBingoEvaluate.md)

