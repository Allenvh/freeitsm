# resolveTenantForNewUser()

Suggest the company for a NEW portal/requester account from its email address.

This is a PRE-FILL, not a rule: the answer is written once to users.tenant_id
and thereafter that column is the truth. We deliberately do NOT re-derive it
when the person raises a ticket, so editing a company's registered domains can
never silently re-file people who already exist.

  1. Single-company install → the Default company. Storing the real id (rather
     than NULL) means that if a second company is added later, everyone who
     already existed stays correctly on Default instead of falling into triage.
  2. Registered domain (tenant_domains) → that company.
  3. Anything else — including every freemail address — → NULL, "not known".
     Freemail is never guessed: two customers share gmail.com, so a domain
     match there would be worse than no answer. An admin sets those by hand.

Note this is stricter than `resolveTenantIdForAddress()`, which also consults
the per-address override list. That list exists to route inbound MAIL, and an
address being on it doesn't establish that the person belongs to the company.

```php
function resolveTenantForNewUser(PDO $conn, string $email): ?int
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1051.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$email` | `string` |  | the new account's email address |

**Returns:** int|null a tenant id, or NULL meaning "company not known".

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1070 | `resolveTenantForNewUser` | read [tenant_domains](/code/tables/tenant_domains.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isFreemailDomain()](/code/functions/isFreemailDomain.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md) - in [completeSelfServiceSso()](/code/functions/completeSelfServiceSso.md)
- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)
- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUsersCreate()](/code/functions/apiUsersCreate.md)
- [includes/ldap.php](/code/files/includes/ldap.php.md) - in [ldapResolveUser()](/code/functions/ldapResolveUser.md)
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::createTicket`
- [self-service/verify-email.php](/code/files/self-service/verify-email.php.md)

