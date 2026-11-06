# ldapPortalProviders()

The LDAP providers a PORTAL sign-in attempt may be tried against.

⚠️ WHY THIS IS NOT `ldapAnalystProviders()` WITH A DIFFERENT FILTER
-------------------------------------------------------------------
Analyst directories are global (`tenant_id IS NULL`) and there is exactly one
set of them, so trying each in turn costs nothing. The portal is different:
on an MSP install each client company can own its own directory, and trying
them all would mean

  1. taking a password someone typed for THEIR employer and offering it to
     every other client's domain controller, and
  2. incrementing the AD lockout counter on every one of those directories —
     so one person fat-fingering their password could lock accounts that
     belong to a different company entirely.

Neither is acceptable, and neither is visible in testing at N=1. So the
candidate list is SCOPED:

  • single-company install  → every enabled LDAP provider (no boundary exists)
  • an identifier with a domain we recognise → that company's directories,
    plus any global ones
  • anything else (a bare username, or an unknown domain) → global only

⚠️ KNOWN LIMIT: on a MULTI-company install, a mailbox-less user signing in
with a bare username can only be matched against a GLOBAL directory — there
is nothing in "w.noemail" to say which company they belong to. A company-owned
directory therefore needs its people to have addresses, or the portal needs a
company hint we don't collect yet. Documented rather than guessed at, because
guessing here means spraying passwords.

```php
function ldapPortalProviders(PDO $conn, ?string $identifier = null): array
```

Defined in [includes/ldap.php](/code/files/includes/ldap.php.md), line 466.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$identifier` | `?string` | `null` | what the user typed (email or username), or null |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 474 | `ldapPortalProviders` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 488 | `ldapPortalProviders` | read [auth_providers](/code/tables/auth_providers.md) |  |
| 496 | `ldapPortalProviders` | read [auth_providers](/code/tables/auth_providers.md) |  |

## Calls

[isMultiTenant()](/code/functions/isMultiTenant.md), [ldapGetProvider()](/code/functions/ldapGetProvider.md), [resolveTenantIdForAddress()](/code/functions/resolveTenantIdForAddress.md)

## Called from

- [api/self-service/login.php](/code/files/api/self-service/login.php.md)

