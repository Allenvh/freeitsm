# dsyncLinkIdentity()

Store the directory's own id for this person, so a rename or OU move is survivable.

```php
function dsyncLinkIdentity(PDO $conn, int $providerId, int $userId, array $p): void
```

Defined in [includes/directory_sync.php](/code/files/includes/directory_sync.php.md), line 868.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$providerId` | `int` |  | auth_providers.id |
| `$userId` | `int` |  | users.id to link |
| `$p` | `array` |  | the person - guid, email, dn (or CardDAV card URL) and carddav_etag are read; an empty guid does nothing |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 878 | `dsyncLinkIdentity` | **insert** [user_sso_identities](/code/tables/user_sso_identities.md) (`user_id`, `provider_id`, `subject`, `email`, `source_ref`, `source_etag`, `linked_datetime`) |  |

## Called from

- [includes/carddav_create.php](/code/files/includes/carddav_create.php.md) - in [cardDavCreateContact()](/code/functions/cardDavCreateContact.md)
- [includes/directory_sync.php](/code/files/includes/directory_sync.php.md) - in [dsyncApplyToExisting()](/code/functions/dsyncApplyToExisting.md), [dsyncCreate()](/code/functions/dsyncCreate.md)

