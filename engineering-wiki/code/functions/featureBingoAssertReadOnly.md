# featureBingoAssertReadOnly()

A card's SQL is read-only by construction, and this makes sure of it: one statement, beginning SELECT. Cards are code in the repository, not user input - this guards against a mistake, not an attacker.

```php
function featureBingoAssertReadOnly(string $sql): void
```

Defined in [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md), line 215.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$sql` | `string` |  | the full statement a card's check will run; throws if it is not a single SELECT |

**Returns:** `void`

## Called from

- [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md) - in [featureBingoRunCheck()](/code/functions/featureBingoRunCheck.md)

