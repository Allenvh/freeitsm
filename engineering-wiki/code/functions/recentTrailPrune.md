# recentTrailPrune()

Keep the log to its cap, by count and by age.

```php
function recentTrailPrune(PDO $conn, int $analystId): void
```

Defined in [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), line 246.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 249 | `recentTrailPrune` | **delete** [analyst_recent_trail](/code/tables/analyst_recent_trail.md) |  |
| 259 | `recentTrailPrune` | **delete** [analyst_recent_trail](/code/tables/analyst_recent_trail.md) |  |

## Called from

- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailWrite()](/code/functions/recentTrailWrite.md)

