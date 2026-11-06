# activeTenantReadFilter()

`activeTenantFilter()` for a LIST READ that should widen in the "All companies" view — the opt-in the note above `isActiveTenantAll()` describes.

🔴 READS ONLY. Never use this to scope an UPDATE, DELETE or reorder: in the
consolidated view it widens to every accessible company, and "act on every
company at once" is not what anybody pressing a button in that view means.
Writes keep `activeTenantFilter()`, which always answers with ONE company.

Opted in so far: Tickets → Users (api/tickets/get_users.php) and the task
list (api/tasks/list.php) — the two a customer reported showing "the last
selected company" under All companies.

```php
function activeTenantReadFilter(
    PDO $conn,
    int $analystId,
    string $alias = 't',
    string $col = 'tenant_id'
): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 846.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'t'` | the table's alias in the calling query; '' for none |
| `$col` | `string` | `'tenant_id'` | the company column on that table (default 'tenant_id') |

**Returns:** `array`

## Calls

[activeTenantFilter()](/code/functions/activeTenantFilter.md), [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/domains/accounts.php](/code/files/api/domains/accounts.php.md)
- [api/tasks/list.php](/code/files/api/tasks/list.php.md)
- [api/tickets/get_users.php](/code/files/api/tickets/get_users.php.md)
- [includes/contract_party.php](/code/files/includes/contract_party.php.md) - in [contractListSql()](/code/functions/contractListSql.md)
- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainListRows()](/code/functions/domainListRows.md), [domainApiLookups()](/code/functions/domainApiLookups.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseListSql()](/code/functions/leaseListSql.md)
- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectListRows()](/code/functions/projectListRows.md)
- [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) - in [rfpListSql()](/code/functions/rfpListSql.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotProjectScope()](/code/functions/warbotProjectScope.md)

