# analystSignInTeamMemberIds()

The members of a team, for the callers that change the team itself.

```php
function analystSignInTeamMemberIds(PDO $conn, int $teamId): array
```

Defined in [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md), line 178.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$teamId` | `int` |  | the team whose members are wanted (analyst_teams.team_id) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 180 | `analystSignInTeamMemberIds` | read [analyst_teams](/code/tables/analyst_teams.md) |  |

## Called from

- [api/tickets/delete_team.php](/code/files/api/tickets/delete_team.php.md)
- [api/tickets/save_team.php](/code/files/api/tickets/save_team.php.md)
- [api/tickets/save_team_analysts.php](/code/files/api/tickets/save_team_analysts.php.md)

