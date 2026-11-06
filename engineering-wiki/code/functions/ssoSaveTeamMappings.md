# ssoSaveTeamMappings()

Replace a provider's group -> team rows. `$rows`: [['group' => .., 'team_id' => ..]]. Blank rows are skipped; an unknown team is refused (ServiceError).

```php
function ssoSaveTeamMappings(PDO $conn, int $providerId, array $rows): void
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 142.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$providerId` | `int` |  | the sign-in provider (auth_providers.id) |
| `$rows` | `array` |  | the new mappings, as [['group' => name, 'team_id' => int], ...]; rows with neither are skipped |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 145 | `ssoSaveTeamMappings` | read [teams](/code/tables/teams.md) |  |
| 155 | `ssoSaveTeamMappings` | **delete** [sso_group_teams](/code/tables/sso_group_teams.md) |  |
| 156 | `ssoSaveTeamMappings` | **insert** [sso_group_teams](/code/tables/sso_group_teams.md) (`provider_id`, `group_name`, `team_id`) |  |

## Called from

- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)

