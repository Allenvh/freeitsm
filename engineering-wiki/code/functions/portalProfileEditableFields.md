# portalProfileEditableFields()

The fields a portal user may change about themselves, in the constant's order.

🔑 NEVER SAVED means all of them, which is what the portal did before this
setting existed - an upgrade must not quietly take a field away. SAVED EMPTY
means none: an administrator unticking everything meant exactly that.

```php
function portalProfileEditableFields(PDO $conn): array
```

Defined in [includes/users.php](/code/files/includes/users.php.md), line 282.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[portalProfileSettingRaw()](/code/functions/portalProfileSettingRaw.md)

## Called from

- [api/system/portal_profile.php](/code/files/api/system/portal_profile.php.md) - in [portalProfileState()](/code/functions/portalProfileState.md)
- [includes/users.php](/code/files/includes/users.php.md) - in [portalProfileAccess()](/code/functions/portalProfileAccess.md)

