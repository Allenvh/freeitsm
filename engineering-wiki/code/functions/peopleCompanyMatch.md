# peopleCompanyMatch()

" AND <col> = ?" for one company - NULL counts as the Default company's.

```php
function peopleCompanyMatch(PDO $conn, int $tenantId, string $qualified): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 55.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$qualified` | `string` |  | the company column to match, alias included, e.g. 't.tenant_id' |

**Returns:** `array`

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [peopleListRows()](/code/functions/peopleListRows.md), [companyDetail()](/code/functions/companyDetail.md), [peopleTickets()](/code/functions/peopleTickets.md), [peopleAssets()](/code/functions/peopleAssets.md), [peopleDomains()](/code/functions/peopleDomains.md), [peopleProjects()](/code/functions/peopleProjects.md), [peopleCoursesForCompany()](/code/functions/peopleCoursesForCompany.md), [peopleForms()](/code/functions/peopleForms.md)

