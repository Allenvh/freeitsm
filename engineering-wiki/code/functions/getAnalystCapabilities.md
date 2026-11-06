# getAnalystCapabilities()

Every capability an analyst effectively holds, as a flat list of keys.

is_admin short-circuits to ALL declared capabilities. Otherwise it's the union of the
capabilities granted by the roles assigned to the analyst directly and by the roles
assigned to any team they belong to — one query, one choke-point, the same
individual-plus-team-unioned shape as module and company access.

Two filters are then applied, and the order matters:
  1. `capFromKey()` drops anything the code no longer defines (and maps retired keys
     through `capAliases()`), so a stale or hand-inserted DB row can never grant.
  2. `capExpandUmbrellas()` adds everything an umbrella grant implies, so holding
     '<module>.manage' gives you the module's individual capabilities too.

```php
function getAnalystCapabilities(PDO $conn, int $analystId): array
```

Defined in [includes/rbac.php](/code/files/includes/rbac.php.md), line 51.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array<int,string>

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 | `getAnalystCapabilities` | read [rbac_role_capabilities](/code/tables/rbac_role_capabilities.md), read [rbac_roles](/code/tables/rbac_roles.md), read [rbac_analyst_roles](/code/tables/rbac_analyst_roles.md), read [rbac_team_roles](/code/tables/rbac_team_roles.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |

## Calls

[analystIsAdmin()](/code/functions/analystIsAdmin.md), [capAll()](/code/functions/capAll.md), [capExpandUmbrellas()](/code/functions/capExpandUmbrellas.md), [capFromKey()](/code/functions/capFromKey.md)

## Called from

- [includes/rbac.php](/code/files/includes/rbac.php.md) - in [analystHasCapability()](/code/functions/analystHasCapability.md)
- [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) - in `AnalystsService::access`

