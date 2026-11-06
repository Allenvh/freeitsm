# wtScopeFor()

The scope this analyst last chose. Defaults to everyone, i.e. today's view.

```php
function wtScopeFor(PDO $conn, int $analystId): string
```

Defined in [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md), line 146.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 151 | `wtScopeFor` | read [user_preferences](/code/tables/user_preferences.md) |  |

## Calls

[wtScopeIsValid()](/code/functions/wtScopeIsValid.md)

## Called from

- [api/watchtower/get_dashboard.php](/code/files/api/watchtower/get_dashboard.php.md)
- [watchtower/index.php](/code/files/watchtower/index.php.md)

