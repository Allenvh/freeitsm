# wtImpersonalOnMine()

What this analyst wants done with the impersonal cards while on "Mine": 'show' (marked as everyone's) or 'hide'.

🔑 A SETTING RATHER THAN A DECISION MADE FOR THEM (Ed). Both answers are
defensible: a dashboard with nothing on it that is not yours is genuinely
cleaner, and losing sight of a degraded service the moment you switch is
genuinely dangerous. Which matters more depends on the person and the desk.

Defaults to 'show', because that is the answer that cannot hide something
urgent from somebody who never opened the settings.

```php
function wtImpersonalOnMine(PDO $conn, int $analystId): string
```

Defined in [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md), line 129.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 134 | `wtImpersonalOnMine` | read [user_preferences](/code/tables/user_preferences.md) |  |

## Called from

- [api/watchtower/get_dashboard.php](/code/files/api/watchtower/get_dashboard.php.md)

