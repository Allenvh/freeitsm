# userDirectoryOwnedFields()

Which person fields are READ-ONLY on a managed record, given the protocol of the provider that manages it and whether that provider writes changes back.

🔴 THE SECOND ARGUMENT IS THE WHOLE POINT OF WRITE-BACK, and leaving it out is
a bug that hides well. The reason these fields are refused is that the next
import would overwrite anything typed here, so an edit that silently reverts
an hour later is worse than one that says no. **An address book with write-back
switched on does not have that problem**: the edit is sent to the card, so the
next import reads back the value the analyst just typed. The justification for
refusing disappears, and the refusal has to disappear with it — otherwise the
feature is unreachable, because the save is rejected before anything is pushed.

An unrecognised or absent protocol falls back to the FULL list, which is the
safe direction: a transport this function has not been taught about is assumed
to own everything, so a future importer cannot silently leave fields editable
that it then overwrites on its next run.

```php
function userDirectoryOwnedFields(?string $protocol, bool $writeBack = false): array
```

Defined in [includes/users.php](/code/files/includes/users.php.md), line 108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$protocol` | `?string` |  | `auth_providers.protocol` — 'ldap', 'oidc', 'carddav' |
| `$writeBack` | `bool` | `false` | `auth_providers.carddav_write_back`; meaningless for any other protocol and ignored there |

**Returns:** `array`

## Called from

- [api/tickets/get_person.php](/code/files/api/tickets/get_person.php.md)
- [api/tickets/get_users.php](/code/files/api/tickets/get_users.php.md)
- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::people`, `AssetsService::assetsForUser`
- [includes/users.php](/code/files/includes/users.php.md) - in [portalProfileAccess()](/code/functions/portalProfileAccess.md)

