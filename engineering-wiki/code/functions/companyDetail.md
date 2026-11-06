# companyDetail()

Everything about one company this analyst may see, or null.

```php
function companyDetail(PDO $conn, int $analystId, int $tenantId): ?array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 276.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 287 | `companyDetail` | read [tenant_domains](/code/tables/tenant_domains.md) |  |
| 293 | `companyDetail` | read [users](/code/tables/users.md) |  |

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [peopleAssets()](/code/functions/peopleAssets.md), [peopleCompanyMatch()](/code/functions/peopleCompanyMatch.md), [peopleContracts()](/code/functions/peopleContracts.md), [peopleCoursesForCompany()](/code/functions/peopleCoursesForCompany.md), [peopleDomains()](/code/functions/peopleDomains.md), [peopleForms()](/code/functions/peopleForms.md), [peopleListRows()](/code/functions/peopleListRows.md), [peopleProjects()](/code/functions/peopleProjects.md), [peopleTickets()](/code/functions/peopleTickets.md)

## Called from

- [people/company.php](/code/files/people/company.php.md)

