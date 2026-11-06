# portalProfileAddressBookWrites()

May an address-book contact change those fields, their change being sent back?

```php
function portalProfileAddressBookWrites(PDO $conn): bool
```

Defined in [includes/users.php](/code/files/includes/users.php.md), line 293.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[portalProfileSettingRaw()](/code/functions/portalProfileSettingRaw.md)

## Called from

- [api/system/portal_profile.php](/code/files/api/system/portal_profile.php.md) - in [portalProfileState()](/code/functions/portalProfileState.md)
- [includes/users.php](/code/files/includes/users.php.md) - in [portalProfileAccess()](/code/functions/portalProfileAccess.md)

