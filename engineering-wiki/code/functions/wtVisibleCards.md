# wtVisibleCards()

Card visibility. Missing row = visible.

```php
function wtVisibleCards(PDO $conn): array
```

Defined in [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md), line 315.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 320 | `wtVisibleCards` | read [watchtower_items](/code/tables/watchtower_items.md) |  |

## Calls

[wtCardKeys()](/code/functions/wtCardKeys.md)

## Called from

- [api/watchtower/get_settings.php](/code/files/api/watchtower/get_settings.php.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

