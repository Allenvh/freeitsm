# isFreemailDomain()

Is this a public free-email / consumer domain (gmail.com, outlook.com, …)?

Such domains must NEVER be registered to a company for shared-intake routing
(two different clients can both send from gmail.com, so a mapping would
mis-route one of them). They still reach support fine — they simply land in
triage and are filed by hand. Used to guard domain registration and to flag
triage rows.

Matches the built-in list (freemailBuiltinDomains) plus any admin-added
custom domains (getCustomFreemailDomains).

```php
function isFreemailDomain(PDO $conn, string $domain): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 1310.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$domain` | `string` |  | a bare domain such as 'gmail.com' (any case) |

**Returns:** `bool`

## Calls

[freemailBuiltinDomains()](/code/functions/freemailBuiltinDomains.md), [getCustomFreemailDomains()](/code/functions/getCustomFreemailDomains.md)

## Called from

- [api/system/add_tenant_domain.php](/code/files/api/system/add_tenant_domain.php.md)
- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)
- [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md)
- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)
- [api/tickets/get_triage.php](/code/files/api/tickets/get_triage.php.md)
- [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md)

