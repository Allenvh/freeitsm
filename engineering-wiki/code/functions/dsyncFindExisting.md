# dsyncFindExisting()

Find the FreeITSM record for a directory person, in order of reliability.

1. the stored GUID          — survives renames AND moves between OUs
  2. the directory username   — for records linked before GUIDs were captured
  3. the email address        — a person who already exists here, see §4.2

Returns [row|null, howMatched].

```php
function dsyncFindExisting(PDO $conn, array $provider, array $p): array
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 424.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$provider` | `array` |  | the auth_providers row - id and tenant_id are read |
| `$p` | `array` |  | the person as `dsyncMapPerson()` (or the CardDAV mapper) returns it |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 430 | `dsyncFindExisting` | read [user_sso_identities](/code/tables/user_sso_identities.md), read [users](/code/tables/users.md) |  |
| 439 | `dsyncFindExisting` | read [users](/code/tables/users.md) |  |
| 452 | `dsyncFindExisting` | read [users](/code/tables/users.md) |  |
| 455 | `dsyncFindExisting` | read [users](/code/tables/users.md) |  |

## Called from

- [includes/carddav_sync.php](/code/files/includes/carddav_sync.php.md) - in [cardDavSyncRun()](/code/functions/cardDavSyncRun.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [directorySyncRun()](/code/functions/directorySyncRun.md)

