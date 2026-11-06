# domainListSelect()

The columns + joins every register read uses.

```php
function domainListSelect(?PDO $conn = null): string
```

Defined in [includes/domains/read.php](/code/files/includes/domains/read.php.md), line 18.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 | `domainListSelect` | read [users](/code/tables/users.md) |  |
| 32 | `domainListSelect` | read [suppliers](/code/tables/suppliers.md), read [contacts](/code/tables/contacts.md), read [analysts](/code/tables/analysts.md) |  |
| 39 | `domainListSelect` | read [domain_lookalikes](/code/tables/domain_lookalikes.md), read [domain_certificates](/code/tables/domain_certificates.md), read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md), read [suppliers](/code/tables/suppliers.md), read [domain_registrar_accounts](/code/tables/domain_registrar_accounts.md), read [analysts](/code/tables/analysts.md), read [contacts](/code/tables/contacts.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[domainCustomerReady()](/code/functions/domainCustomerReady.md), [domainPartiesReady()](/code/functions/domainPartiesReady.md)

## Called from

- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainListRows()](/code/functions/domainListRows.md), [domainDetail()](/code/functions/domainDetail.md)

