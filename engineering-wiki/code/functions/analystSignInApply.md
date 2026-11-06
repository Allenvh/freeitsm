# analystSignInApply()

Bring one analyst's stored method in line with their teams, if they follow their team. Returns what happened, for the caller to report.

```php
function analystSignInApply(PDO $conn, int $analystId): string
```

Defined in [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md), line 130.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** string 'own' (not following) | 'none' | 'conflict' | 'unchanged' | 'changed'

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 135 | `analystSignInApply` | read [analysts](/code/tables/analysts.md) |  |
| 151 | `analystSignInApply` | **update** [analysts](/code/tables/analysts.md) (`auth_provider_id`, `last_modified_datetime`) |  |

## Calls

[analystSignInFromTeams()](/code/functions/analystSignInFromTeams.md), [analystSignInReady()](/code/functions/analystSignInReady.md)

## Called from

- [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md) - in [analystSignInApplyMany()](/code/functions/analystSignInApplyMany.md)

