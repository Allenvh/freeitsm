# analystSignInVerdict()

Turn one analyst's team methods into a verdict.

```php
function analystSignInVerdict(array $teams): array
```

Defined in [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md), line 106.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$teams` | `array` |  | one analyst's entry from `analystSignInTeamMethods()` - a list of {team_id, team_name, key, label} |

**Returns:** array{status:string, key:?int, teams:array} status 'none'     - no active team of theirs sets a method 'agreed'   - one method; key is it (0 = local password) 'conflict' - two or more different methods; key is null

## Called from

- [api/tickets/get_analysts.php](/code/files/api/tickets/get_analysts.php.md)
- [api/tickets/get_teams.php](/code/files/api/tickets/get_teams.php.md)
- [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md) - in [analystSignInFromTeams()](/code/functions/analystSignInFromTeams.md)

