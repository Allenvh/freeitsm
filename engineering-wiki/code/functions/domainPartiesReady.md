# domainPartiesReady()

Has Database Verification added the #162 columns (customer_supplier_id, customer_contact_id, tech_analyst_id) yet? All three arrive together.

```php
function domainPartiesReady(PDO $conn): bool
```

Defined in [includes/domains/customer.php](/code/files/includes/domains/customer.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `domainPartiesReady` | read [domains](/code/tables/domains.md) |  |

## Called from

- [api/domains/people.php](/code/files/api/domains/people.php.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainSelect()](/code/functions/apiDomainSelect.md)
- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainListSelect()](/code/functions/domainListSelect.md), [domainApiLookups()](/code/functions/domainApiLookups.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleSupplierDomains()](/code/functions/peopleSupplierDomains.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::fieldReady`, `DomainsService::normaliseParties`

