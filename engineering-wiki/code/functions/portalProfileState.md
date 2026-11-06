# portalProfileState()

What the page needs to draw itself - always read back from the database.

```php
function portalProfileState(PDO $conn): array
```

Defined in [api/system/portal_profile.php](/code/files/api/system/portal_profile.php.md), line 22.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 | `portalProfileState` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Calls

[portalProfileAddressBookWrites()](/code/functions/portalProfileAddressBookWrites.md), [portalProfileEditableFields()](/code/functions/portalProfileEditableFields.md)

## Called from

- [api/system/portal_profile.php](/code/files/api/system/portal_profile.php.md)

