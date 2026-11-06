# ssoProfileSyncLocks()

Is this provider rewriting profile details on every sign-in? (discussion #155)

True only for an OIDC provider set to 'always'. When it is, the synced
fields are shown read-only on My Account (portal) and My details (analyst),
because anything typed there would be put back at the next sign-in.

Deliberately NOT true for LDAP or CardDAV: they keep records up to date
their own way (directory sync, address-book write-back), with their own
rules about what is editable - see `portalProfileAccess()`.

```php
function ssoProfileSyncLocks(?string $protocol, ?string $syncMode): bool
```

Defined in [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), line 89.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$protocol` | `?string` |  | the provider's protocol, e.g. 'oidc' or 'ldap' |
| `$syncMode` | `?string` |  | the provider's profile_sync_mode; only 'always' locks |

**Returns:** `bool`

## Called from

- [includes/sso_identity.php](/code/files/includes/sso_identity.php.md) - in [ssoAnalystProfileLocked()](/code/functions/ssoAnalystProfileLocked.md)
- [includes/users.php](/code/files/includes/users.php.md) - in [portalProfileAccess()](/code/functions/portalProfileAccess.md)

