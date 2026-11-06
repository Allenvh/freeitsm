# ldapResolveUser()

Map a directory user to a SELF-SERVICE PORTAL account (`users`), creating one if needed. The portal twin of `ldapResolveAnalyst()`.

Returns ['ok' => true, 'user_id' => int] or ['ok' => false, 'error' => string].

THREE DELIBERATE DIVERGENCES FROM THE ANALYST VERSION
-----------------------------------------------------
1. 🔑 **No mailbox means NULL, never `''`.** The analyst side stores `''`
   (#872) and gets away with it only because `analysts.email` is not unique.
   `users.email` IS unique, so the second mailbox-less warehouse worker would
   collide on the first. MySQL allows many NULLs in a unique index, so NULL is
   the representation that actually scales. Anything writing `''` here
   re-introduces the bug.

2. 🔑 **An unclaimed existing account is CLAIMED, not refused.** The analyst
   version rejects a local-only match as strict isolation. The portal cannot:
   `users` rows are auto-created without a password all over the product —
   inbound email, web chat, WhatsApp, workflows, the API — so the person
   signing in for the first time usually ALREADY has a row holding their
   ticket history. Refusing would strand them beside their own tickets. This
   matches what portal OIDC already does. An account belonging to a DIFFERENT
   provider is still refused.

3. 🔑 **The company comes from the provider first.** A directory owned by a
   company vouches for its people, which is the only thing that works for a
   user with no address to derive a domain from — precisely the people this
   exists for. Domain matching is the fallback for everyone else.

```php
function ldapResolveUser(PDO $conn, array $provider, array $ldapUser): array
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 743.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$provider` | `array` |  | the provider row from `ldapGetProvider()` |
| `$ldapUser` | `array` |  | the 'user' from a successful `ldapAuthenticate()` (guid, username, email, name) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 754 | `ldapResolveUser` | read [user_sso_identities](/code/tables/user_sso_identities.md) |  |
| 762 | `ldapResolveUser` | read [users](/code/tables/users.md) |  |
| 778 | `ldapResolveUser` | **update** [user_sso_identities](/code/tables/user_sso_identities.md) (`last_login_datetime`, `email`) |  |
| 783 | `ldapResolveUser` | **update** [users](/code/tables/users.md) (`email`) |  |
| 797 | `ldapResolveUser` | read [users](/code/tables/users.md) |  |
| 808 | `ldapResolveUser` | **update** [users](/code/tables/users.md) (`auth_provider_id`) |  |
| 816 | `ldapResolveUser` | **update** [users](/code/tables/users.md) (`username`) |  |
| 848 | `ldapResolveUser` | **insert** [users](/code/tables/users.md) (`email`, `username`, `display_name`, `auth_provider_id`, `tenant_id`, `created_at`) |  |
| 861 | `ldapResolveUser` | **insert** [user_sso_identities](/code/tables/user_sso_identities.md) (`user_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime`) |  |

## Calls

[resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md), [ssoClearDanglingLink()](/code/functions/ssoClearDanglingLink.md)

## Called from

- [api/self-service/login.php](/code/files/api/self-service/login.php.md)

