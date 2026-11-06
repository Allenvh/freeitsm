# analystSignInFromTeams()

What one analyst's teams say, without changing anything.

```php
function analystSignInFromTeams(PDO $conn, int $analystId): array
```

Defined in [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md), line 119.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[analystSignInTeamMethods()](/code/functions/analystSignInTeamMethods.md), [analystSignInVerdict()](/code/functions/analystSignInVerdict.md)

## Called from

- [includes/analyst_signin.php](/code/files/includes/analyst_signin.php.md) - in [analystSignInApply()](/code/functions/analystSignInApply.md)

