# rpTenantClause()

The company a pack reports on, as a WHERE fragment for `$qualified`.

'active'  - whatever company the viewer is working in right now (the default,
              and what every dashboard does)
  'all'     - every company the viewer may see
  <id>      - one company, which the VIEWER must be able to access

A shared pack is read with the viewer's rights, not the author's: if the author
chose a company the viewer cannot see, the viewer gets an error, never the data.

```php
function rpTenantClause(PDO $conn, int $analystId, $tenant, string $qualified): array
```

Defined in [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md), line 115.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$tenant` |  |  | the pack's company: 'active' (or null/''), 'all', or a company id the viewer must be able to reach |
| `$qualified` | `string` |  | the company column to filter, alias included, e.g. 't.tenant_id' |

**Returns:** array{0:string,1:array} [" AND ...", params], or ['', []] at N=1

## Calls

[allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/reporting/packs/context.php](/code/files/api/reporting/packs/context.php.md)
- [includes/report_packs/blocks_assets.php](/code/files/includes/report_packs/blocks_assets.php.md) - in [rpAssetsBreakdown()](/code/functions/rpAssetsBreakdown.md), [rpAssetsKpis()](/code/functions/rpAssetsKpis.md), [rpAssetsExpiring()](/code/functions/rpAssetsExpiring.md)
- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectClause()](/code/functions/rpProjectClause.md)
- [includes/report_packs/blocks_software.php](/code/files/includes/report_packs/blocks_software.php.md) - in [rpSoftwareScope()](/code/functions/rpSoftwareScope.md)
- [includes/report_packs/blocks_tickets.php](/code/files/includes/report_packs/blocks_tickets.php.md) - in [rpTicketWhere()](/code/functions/rpTicketWhere.md)

