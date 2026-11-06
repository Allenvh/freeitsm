# resolveTenantIdForAddress()

Resolve an email address to the tenant that owns it, for LOGIN routing (which IdP should this person be sent to). Mirrors the email-routing rule (exact sender address → registered domain), but without the mailbox step.

Returns the tenant id, or null if the address maps to no company (unknown
domain, or a freemail address) → caller falls back to local login.

```php
function resolveTenantIdForAddress(PDO $conn, string $email): ?int
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1233.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$email` | `string` |  | the email address typed at sign-in |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1239 | `resolveTenantIdForAddress` | read [tenant_sender_addresses](/code/tables/tenant_sender_addresses.md) |  |
| 1249 | `resolveTenantIdForAddress` | read [tenant_domains](/code/tables/tenant_domains.md) |  |

## Called from

- [api/auth/resolve_login.php](/code/files/api/auth/resolve_login.php.md)
- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)
- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapPortalProviders()](/code/functions/ldapPortalProviders.md)

