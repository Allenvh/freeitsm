# ssoSeenGroups()

Groups this provider has sent: [['group' => code, 'label' => name|null, 'last_seen' => datetime]], named ones first, then most recently seen.

```php
function ssoSeenGroups(PDO $conn, int $providerId): array
```

Defined in [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), line 166.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$providerId` | `int` |  | the sign-in provider (auth_providers.id) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 169 | `ssoSeenGroups` | read [sso_groups_seen](/code/tables/sso_groups_seen.md) |  |

## Calls

[ssoClaimsReady()](/code/functions/ssoClaimsReady.md)

## Called from

- [api/system/get_sso_providers.php](/code/files/api/system/get_sso_providers.php.md)

