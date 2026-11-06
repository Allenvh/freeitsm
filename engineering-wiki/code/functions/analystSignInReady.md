# analystSignInReady()

Have the columns this needs been added yet?

⚠️ New code reaches an install before System -> Database Verification runs.
api/tickets/get_analysts.php feeds assignee lists all over the application, so
selecting a column that is not there yet would break far more than this
feature. Every caller checks this first and carries on without it.

```php
function analystSignInReady(PDO $conn): bool
```

Defined in [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 53 | `analystSignInReady` | read [analysts](/code/tables/analysts.md) |  |
| 54 | `analystSignInReady` | read [teams](/code/tables/teams.md) |  |

## Called from

- [api/tickets/get_analysts.php](/code/files/api/tickets/get_analysts.php.md)
- [api/tickets/get_teams.php](/code/files/api/tickets/get_teams.php.md)
- [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md)
- [api/tickets/save_team.php](/code/files/api/tickets/save_team.php.md)
- [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md) - in [analystSignInApply()](/code/functions/analystSignInApply.md), [analystSignInFollowerIds()](/code/functions/analystSignInFollowerIds.md)
- [includes/sso_claims.php](/code/files/includes/sso_claims.php.md) - in [ssoApplyGroupAccess()](/code/functions/ssoApplyGroupAccess.md)

