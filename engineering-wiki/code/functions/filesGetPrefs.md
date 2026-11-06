# filesGetPrefs()

Per-person desktop preferences, in user_preferences with a files_ prefix.

```php
function filesGetPrefs(PDO $conn, int $analystId): array
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 140.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 142 | `filesGetPrefs` | read [user_preferences](/code/tables/user_preferences.md) |  |

## Called from

- [files/index.php](/code/files/files/index.php.md)

