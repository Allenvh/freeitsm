# portalPrefSet()

Store one preference. Returns false if it could not be stored — the caller decides whether that is worth telling anybody, and for a layout toggle it is not: the layout still changed, only the memory of it failed.

```php
function portalPrefSet(PDO $conn, int $userId, string $key, string $value): bool
```

Defined in [includes/portal_preferences.php](/code/files/includes/portal_preferences.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the portal user (users id); 0 or less stores nothing |
| `$key` | `string` |  | the preference_key, e.g. 'kb_layout'; '' stores nothing |
| `$value` | `string` |  | the value to store, as text |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `portalPrefSet` | read [portal_user_preferences](/code/tables/portal_user_preferences.md) |  |
| 68 | `portalPrefSet` | **update** [portal_user_preferences](/code/tables/portal_user_preferences.md) (`preference_value`, `updated_datetime`) |  |
| 72 | `portalPrefSet` | **insert** [portal_user_preferences](/code/tables/portal_user_preferences.md) (`user_id`, `preference_key`, `preference_value`, `updated_datetime`) |  |

## Called from

- [api/self-service/preference.php](/code/files/api/self-service/preference.php.md)

