# portalProfileAccess()

How the portal treats one person's contact details. One answer, used by both the read and the save, so the form and the endpoint cannot disagree.

```php
function portalProfileAccess(PDO $conn, int $userId): ?array
```

Defined in [includes/users.php](/code/files/includes/users.php.md), line 311.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the portal user (users.id) whose details are being read or saved |

**Returns:** array|null null when the person does not exist. Otherwise: fields       - what the portal offers them (the administrator's choice) locked       - of those, the ones they may not change: a directory owns them address_book - their changes are sent to an address book, and only saved here once it has accepted them managed      - a directory keeps this record up to date at all row          - the record's current values, for the save's three-way merge

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 318 | `portalProfileAccess` | read [users](/code/tables/users.md), read [auth_providers](/code/tables/auth_providers.md) |  |

## Calls

[portalProfileAddressBookWrites()](/code/functions/portalProfileAddressBookWrites.md), [portalProfileEditableFields()](/code/functions/portalProfileEditableFields.md), [ssoJitColumnsReady()](/code/functions/ssoJitColumnsReady.md), [ssoProfileSyncLocks()](/code/functions/ssoProfileSyncLocks.md), [userDirectoryOwnedFields()](/code/functions/userDirectoryOwnedFields.md)

## Called from

- [api/self-service/get_profile.php](/code/files/api/self-service/get_profile.php.md)
- [api/self-service/update_profile.php](/code/files/api/self-service/update_profile.php.md)

