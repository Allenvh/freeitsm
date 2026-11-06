# portalPrefGet()

One preference for one portal user, or `$default`.

```php
function portalPrefGet(PDO $conn, int $userId, string $key, string $default = ''): string
```

Defined in [includes/portal_preferences.php](/code/files/includes/portal_preferences.php.md), line 28.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the portal user (users id); 0 or less gives `$default` |
| `$key` | `string` |  | the preference_key, e.g. 'kb_layout'; '' gives `$default` |
| `$default` | `string` | `''` | returned when unset, unreadable, or the table is absent |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 | `portalPrefGet` | read [portal_user_preferences](/code/tables/portal_user_preferences.md) |  |

## Called from

- [api/self-service/preference.php](/code/files/api/self-service/preference.php.md)
- [includes/portal_preferences.php](/code/files/includes/portal_preferences.php.md) - in [portalKbLayout()](/code/functions/portalKbLayout.md)

