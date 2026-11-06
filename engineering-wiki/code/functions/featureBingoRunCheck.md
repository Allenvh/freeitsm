# featureBingoRunCheck()

Run one check. Returns true / false; an error (missing table or column, a malformed check) is false and is written to `$error`.

```php
function featureBingoRunCheck(PDO $conn, array $check, ?string &$error = null)
```

Defined in [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md), line 156.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$check` | `array` |  | a card's check: ['rows', table, where, min], ['setting', key, op, value], ['sql', select, min], or ['any'\|'all', [check, ...]] - nests |
| `$error` | `?string` | `null` | out: null, or why the check could not run (sub-check errors joined with "; ") |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 171 | `featureBingoRunCheck` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[featureBingoAssertReadOnly()](/code/functions/featureBingoAssertReadOnly.md)

## Called from

- [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md) - in [featureBingoRunCheck()](/code/functions/featureBingoRunCheck.md), [featureBingoEvaluate()](/code/functions/featureBingoEvaluate.md)

