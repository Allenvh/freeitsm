# apiDomainSelect()

The SELECT for domains, with status, registrar, account, owner, technical contact and company names and, once Database Verification has added them, the customer columns.

```php
function apiDomainSelect(?PDO $conn = null): string
```

Defined in [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `apiDomainSelect` | read [users](/code/tables/users.md) |  |
| 44 | `apiDomainSelect` | read [suppliers](/code/tables/suppliers.md), read [contacts](/code/tables/contacts.md), read [analysts](/code/tables/analysts.md) |  |
| 48 | `apiDomainSelect` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md), read [suppliers](/code/tables/suppliers.md), read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md), read [analysts](/code/tables/analysts.md), read [contacts](/code/tables/contacts.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[domainCustomerReady()](/code/functions/domainCustomerReady.md), [domainPartiesReady()](/code/functions/domainPartiesReady.md)

## Called from

- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiLoadDomain()](/code/functions/apiLoadDomain.md), [apiDomainsList()](/code/functions/apiDomainsList.md)

