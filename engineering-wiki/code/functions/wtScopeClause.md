# wtScopeClause()

A SQL fragment restricting `$column` to the chosen scope, plus its parameters. Returns ['', []] for "everyone", so a caller can concatenate unconditionally.

⚠️ The column is INTERPOLATED, so it must never come from a request. Every
caller passes a literal — `t.assigned_analyst_id`, `c.assigned_to_id` — and
that is the only safe way to use this.

```php
function wtScopeClause(PDO $conn, int $analystId, string $scope, string $column): array
```

Defined in [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md), line 248.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$scope` | `string` |  | WT_SCOPE_MINE, WT_SCOPE_TEAM or WT_SCOPE_ALL ('all' gives no clause) |
| `$column` | `string` |  | the owner column with its alias, e.g. 't.assigned_analyst_id' - always a literal, never input |

**Returns:** `array`

## Calls

[wtTeamAnalystIds()](/code/functions/wtTeamAnalystIds.md)

## Called from

- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

