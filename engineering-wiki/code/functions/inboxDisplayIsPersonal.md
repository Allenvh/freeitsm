# inboxDisplayIsPersonal()

Has this analyst chosen for themselves, or are they following the install default?

```php
function inboxDisplayIsPersonal(PDO $conn, int $analystId): bool
```

Defined in [includes/inbox_display.php](/code/files/includes/inbox_display.php.md), line 189.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 196 | `inboxDisplayIsPersonal` | read [user_preferences](/code/tables/user_preferences.md) |  |

## Called from

- [api/tickets/save_inbox_display.php](/code/files/api/tickets/save_inbox_display.php.md)
- [tickets/settings/index.php](/code/files/tickets/settings/index.php.md)

