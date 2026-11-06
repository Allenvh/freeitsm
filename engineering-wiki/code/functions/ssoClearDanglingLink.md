# ssoClearDanglingLink()

Drop a sign-in link whose account no longer exists.

A link can outlive the account it points at. Both identity tables declare
ON DELETE CASCADE, but anything that deletes rows with FOREIGN_KEY_CHECKS
off bypasses the cascade — the demo data importer used to do exactly that
when it emptied `analysts` and `users` (#1297) — and the link is left behind,
pointing at an id that is gone.

That is not a cosmetic leftover. Every sign-in path looks a person up by
(provider, subject) FIRST, so a dangling link WINS that lookup, and email
matching and just-in-time provisioning both live in the branch past it. The
person is locked out permanently, and re-creating their account by hand does
not help, because the link still points at the old id. It happened to a real
user, and the message he saw ("Your account is no longer available") named
nothing he could act on.

Clearing the link puts them back on the path a first-time sign-in takes, with
the same verified-email and provider-assignment checks — no account becomes
reachable that a first-time user could not already reach. Deleting an account
through the interface already cascades the link away and already allows
just-in-time re-creation, so this adds no new exposure.

`$table` is a caller-supplied literal and is checked against a fixed list; it
never comes from request input.

```php
function ssoClearDanglingLink(PDO $conn, string $table, int $providerId, string $sub): void
```

Defined in [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), line 44.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | 'analyst_sso_identities' or 'user_sso_identities' - a developer literal; anything else throws |
| `$providerId` | `int` |  | the sign-in provider (auth_providers.id) |
| `$sub` | `string` |  | the subject (sub claim) the provider sent for this person |

**Returns:** `void`

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) - in [completeSelfServiceSso()](/code/functions/completeSelfServiceSso.md)
- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapResolveAnalyst()](/code/functions/ldapResolveAnalyst.md), [ldapResolveUser()](/code/functions/ldapResolveUser.md)

