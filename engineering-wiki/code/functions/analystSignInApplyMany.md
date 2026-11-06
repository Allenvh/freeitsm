# analystSignInApplyMany()

Apply to several analysts and count the outcomes.

```php
function analystSignInApplyMany(PDO $conn, array $analystIds): array
```

Defined in [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md), line 162.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystIds` | `array` |  | the analysts to bring into line; cast to int, duplicates and ids <= 0 skipped |

**Returns:** array{changed:int, conflict:int}

## Calls

[analystSignInApply()](/code/functions/analystSignInApply.md)

## Called from

- [api/system/delete_sso_provider.php](/code/files/api/system/delete_sso_provider.php.md)
- [api/tickets/delete_team.php](/code/files/api/tickets/delete_team.php.md)
- [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md)
- [api/tickets/save_analyst_teams.php](/code/files/api/tickets/save_analyst_teams.php.md)
- [api/tickets/save_team.php](/code/files/api/tickets/save_team.php.md)
- [api/tickets/save_team_analysts.php](/code/files/api/tickets/save_team_analysts.php.md)
- [includes/sso_claims.php](/code/files/includes/sso_claims.php.md) - in [ssoApplyGroupAccess()](/code/functions/ssoApplyGroupAccess.md)

