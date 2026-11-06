# featureBingoCards()

Every card, validated. A card with a missing field or an unknown module or tier is left out and reported in `$problems`, never shown half-formed.

```php
function featureBingoCards(?array &$problems = null): array
```

Defined in [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md), line 114.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$problems` | `?array` | `null` | out: reset to [] and filled with one "file#index: what is wrong" string per bad card |

**Returns:** `array`

## Calls

[featureBingoCategories()](/code/functions/featureBingoCategories.md), [featureBingoLocalDir()](/code/functions/featureBingoLocalDir.md), [featureBingoModules()](/code/functions/featureBingoModules.md)

## Called from

- [api/system/feature_bingo.php](/code/files/api/system/feature_bingo.php.md)
- [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md) - in [featureBingoEvaluate()](/code/functions/featureBingoEvaluate.md)

