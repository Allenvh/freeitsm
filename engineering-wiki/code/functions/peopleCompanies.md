# peopleCompanies()

The companies this analyst can access, with how many people are in each.

```php
function peopleCompanies(PDO $conn, int $analystId): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 125.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 131 | `peopleCompanies` | read [tenants](/code/tables/tenants.md) |  |
| 135 | `peopleCompanies` | read [users](/code/tables/users.md) |  |

## Calls

[getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [peopleCompanyCards()](/code/functions/peopleCompanyCards.md)
- [people/index.php](/code/files/people/index.php.md)

