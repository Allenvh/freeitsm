# domainCustomerReady()

Has Database Verification added domains.customer_user_id yet?

```php
function domainCustomerReady(PDO $conn): bool
```

Defined in [includes/domains/customer.php](/code/files/includes/domains/customer.php.md), line 24.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 | `domainCustomerReady` | read [domains](/code/tables/domains.md) |  |

## Called from

- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainSelect()](/code/functions/apiDomainSelect.md)
- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainListSelect()](/code/functions/domainListSelect.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleDomains()](/code/functions/peopleDomains.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::fieldReady`

