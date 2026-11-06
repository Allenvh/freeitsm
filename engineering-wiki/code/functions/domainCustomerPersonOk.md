# domainCustomerPersonOk()

May `$userId` be the customer of a domain in company `$tenantId`? An active user in that company; on a single-company install, any active user. A user with no company counts as the Default company's, as everywhere else.

```php
function domainCustomerPersonOk(PDO $conn, int $userId, ?int $tenantId): bool
```

Defined in [includes/domains/customer.php](/code/files/includes/domains/customer.php.md), line 49.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$userId` | `int` |  | the users.id proposed as the customer contact |
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 | `domainCustomerPersonOk` | read [users](/code/tables/users.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::validateField`

