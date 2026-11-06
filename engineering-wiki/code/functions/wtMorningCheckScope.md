# wtMorningCheckScope()

"Mine" for a morning check, which is not a column.

🔴 A CHECK IS ROUTED THROUGH ITS GROUP, not by an analyst id on the check.
On a real install `morningChecks_Checks.AssignedAnalystID` is empty on every
row — routing is done by putting a check in a group and pointing the group at
a team or a person. Scoping on the check's own column therefore produces a
CONFIDENT ZERO for everybody: "no checks are yours", which reads as "nothing
to do" on the one card whose whole job is to say otherwise. Watchtower has
shipped that class of bug before, counting statuses by names nothing matched.

So "mine" is: assigned to me directly, OR in a group pointed at me, OR in a
group pointed at a team I am in.

Expects the query to expose `c` (checks) and `g` (groups, LEFT JOINed).

```php
function wtMorningCheckScope(PDO $conn, int $analystId, string $scope): array
```

Defined in [includes/watchtower_settings.php](/code/files/includes/watchtower_settings.php.md), line 207.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$scope` | `string` |  | WT_SCOPE_MINE, WT_SCOPE_TEAM or WT_SCOPE_ALL ('all' gives no clause) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 216 | `wtMorningCheckScope` | read [analyst_teams](/code/tables/analyst_teams.md) |  |

## Calls

[wtTeamAnalystIds()](/code/functions/wtTeamAnalystIds.md)

## Called from

- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

