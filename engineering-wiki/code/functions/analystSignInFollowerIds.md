# analystSignInFollowerIds()

Every analyst who follows their team - used when a provider is deleted.

```php
function analystSignInFollowerIds(PDO $conn): array
```

Defined in [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md), line 186.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 191 | `analystSignInFollowerIds` | read [analysts](/code/tables/analysts.md) |  |

## Calls

[analystSignInReady()](/code/functions/analystSignInReady.md)

## Called from

- [api/system/delete_sso_provider.php](/code/files/api/system/delete_sso_provider.php.md)
- [api/tickets/get_teams.php](/code/files/api/tickets/get_teams.php.md)

