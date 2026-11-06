# analystSignInTeamMethods()

The sign-in methods set by analysts' active teams, grouped by analyst.

```php
function analystSignInTeamMethods(PDO $conn, ?int $analystId = null): array
```

Defined in [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `?int` | `null` | one analyst, or null for everyone (System -> Analysts) |

**Returns:** array<int, array<int, array{team_id:int, team_name:string, key:int, label:?string}>> analyst_id => their teams' methods; key 0 = local password, otherwise the auth_providers.id, and label is that provider's name.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 73 | `analystSignInTeamMethods` | read [analyst_teams](/code/tables/analyst_teams.md), read [teams](/code/tables/teams.md), read [auth_providers](/code/tables/auth_providers.md) |  |

## Called from

- [api/tickets/get_analysts.php](/code/files/api/tickets/get_analysts.php.md)
- [api/tickets/get_teams.php](/code/files/api/tickets/get_teams.php.md)
- [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md) - in [analystSignInFromTeams()](/code/functions/analystSignInFromTeams.md)

