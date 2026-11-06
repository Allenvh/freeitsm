# domainLookup()

Look a domain up at its registry.

```php
function domainLookup(PDO $conn, string $name): array
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 145.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$name` | `string` |  | a stored domain name, already through `domainNormalise()` (ASCII, lower case) |

**Returns:** array The normalised result. Always has 'ok' and 'source'. On success: registrar, registrant, registration_date, expiry_date, updated_date, last_renewed_date (Y-m-d or null), statuses (list, lower-case words), nameservers (list), dnssec (bool|null), transfer_lock (bool|null), registry_lock (bool|null), redemption (bool), pending_delete (bool), expiry_published (bool — false when the registry does not publish one). On failure: error (plain English), not_found (bool — the registry says there is no such registration).

## Calls

[domainRdapBootstrap()](/code/functions/domainRdapBootstrap.md), [domainRdapLookup()](/code/functions/domainRdapLookup.md), [domainTld()](/code/functions/domainTld.md), [domainWhoisLookup()](/code/functions/domainWhoisLookup.md)

## Called from

- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainScheduledRun()](/code/functions/domainScheduledRun.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::refreshLookup`

