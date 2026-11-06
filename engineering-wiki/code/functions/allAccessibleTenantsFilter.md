# allAccessibleTenantsFilter()

The consolidated-view predicate: every company this analyst may see. The ONE copy of it, shared by `ticketTenantFilter()` and `activeTenantReadFilter()`.

🔴 "ALL" MEANS "EVERY COMPANY I MAY SEE", NEVER "NO FILTER". The clause always
names an explicit id list — returning ['', []] would read as "multi-tenancy is
dormant" and hand every company's rows to everybody.

```php
function allAccessibleTenantsFilter(PDO $conn, int $analystId, string $qualified): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 809.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$qualified` | `string` |  | the already-qualified column, e.g. "t.tenant_id" |

**Returns:** `array`

## Calls

[getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractAssetReachSql()](/code/functions/contractAssetReachSql.md)
- [includes/contract_party.php](/code/files/includes/contract_party.php.md) - in [contractVisibilitySql()](/code/functions/contractVisibilitySql.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseVisibilitySql()](/code/functions/leaseVisibilitySql.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleScope()](/code/functions/peopleScope.md)
- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)
- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectChoices()](/code/functions/rpProjectChoices.md)
- [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md) - in [rpTenantClause()](/code/functions/rpTenantClause.md)
- [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md) - in [rfpVisibilitySql()](/code/functions/rfpVisibilitySql.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [ticketTenantFilter()](/code/functions/ticketTenantFilter.md), [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md)

