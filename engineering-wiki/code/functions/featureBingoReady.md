# featureBingoReady()

Has Database Verification created the "Not for us" table yet?

```php
function featureBingoReady(PDO $conn): bool
```

Defined in [includes/feature_bingo.php](/code/files/includes/feature_bingo.php.md), line 258.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 260 | `featureBingoReady` | read [feature_bingo_dismissed](/code/tables/feature_bingo_dismissed.md) |  |

## Called from

- [api/system/feature_bingo.php](/code/files/api/system/feature_bingo.php.md)

