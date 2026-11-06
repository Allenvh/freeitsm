# peopleListRows()

People the analyst can see, newest-relevant first by name.

```php
function peopleListRows(PDO $conn, int $analystId, array $f = [], int $limit = 200): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 85.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$f` | `array` | `[]` | q, company (tenant id), status ('active' default \| 'leavers' \| 'all') |
| `$limit` | `int` | `200` | the most rows to return |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 106 | `peopleListRows` | read [users](/code/tables/users.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [peopleCompanyMatch()](/code/functions/peopleCompanyMatch.md), [peopleName()](/code/functions/peopleName.md), [peopleScope()](/code/functions/peopleScope.md)

## Called from

- [api/people/list.php](/code/files/api/people/list.php.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [companyDetail()](/code/functions/companyDetail.md)

