# ssoSaveGroupLabels()

Name the groups a provider has sent. `$labels`: [['group' => code, 'label' => name]]. Only groups it has actually sent can be named; a blank name clears it.

```php
function ssoSaveGroupLabels(PDO $conn, int $providerId, array $labels): void
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 183.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$providerId` | `int` |  | the sign-in provider (auth_providers.id) |
| `$labels` | `array` |  | [['group' => code, 'label' => name], ...]; a blank label clears it |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 185 | `ssoSaveGroupLabels` | **update** [sso_groups_seen](/code/tables/sso_groups_seen.md) (`label`) |  |

## Called from

- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)

