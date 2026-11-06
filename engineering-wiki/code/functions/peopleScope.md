# peopleScope()

" AND (<col> IN (accessible) [OR <col> IS NULL])", or '' on a single-company install.

```php
function peopleScope(PDO $conn, int $analystId, string $qualified): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 44.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$qualified` | `string` |  | the company column to filter on, alias included, e.g. 'u.tenant_id' |

**Returns:** `array`

## Calls

[allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [peopleListRows()](/code/functions/peopleListRows.md), [peopleCompanyCards()](/code/functions/peopleCompanyCards.md), [personDetail()](/code/functions/personDetail.md), [peopleTickets()](/code/functions/peopleTickets.md), [peopleAssets()](/code/functions/peopleAssets.md), [peopleDomains()](/code/functions/peopleDomains.md), [peopleProjects()](/code/functions/peopleProjects.md), [peopleContractorTasks()](/code/functions/peopleContractorTasks.md), [peopleSupplierAssets()](/code/functions/peopleSupplierAssets.md), [peopleSupplierDomains()](/code/functions/peopleSupplierDomains.md)

