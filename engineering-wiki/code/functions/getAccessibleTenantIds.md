# getAccessibleTenantIds()

The tenant ids an analyst may access. Company access is ADDITIVE from two sources, unioned: 1. the analyst's own grant (can_access_all_tenants flag / analyst_tenant_access); 2. the teams they belong to (a team flagged can_access_all_tenants grants every company; otherwise its team_tenant_access rows). So an analyst can reach a company if their own access OR any of their teams grants it. A team grants nothing until explicitly configured, so this only ever widens access once an admin sets it up. (Cached per analyst per request.)

```php
function getAccessibleTenantIds(PDO $conn, int $analystId): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 193.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 202 | `getAccessibleTenantIds` | read [tenants](/code/tables/tenants.md) |  |
| 206 | `getAccessibleTenantIds` | read [analysts](/code/tables/analysts.md) |  |
| 216 | `getAccessibleTenantIds` | read [analyst_teams](/code/tables/analyst_teams.md), read [teams](/code/tables/teams.md) |  |
| 230 | `getAccessibleTenantIds` | read [analyst_tenant_access](/code/tables/analyst_tenant_access.md), read [team_tenant_access](/code/tables/team_tenant_access.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 240 | `getAccessibleTenantIds` | read [analyst_tenant_access](/code/tables/analyst_tenant_access.md) |  |

## Calls

[tenancyTablesReady()](/code/functions/tenancyTablesReady.md)

## Called from

- [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md)
- [api/contracts/customer_lookup.php](/code/files/api/contracts/customer_lookup.php.md)
- [api/forms/lookup_search.php](/code/files/api/forms/lookup_search.php.md)
- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [api/reporting/packs/catalogue.php](/code/files/api/reporting/packs/catalogue.php.md)
- [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md)
- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md)
- [api/tickets/get_ticket_counts.php](/code/files/api/tickets/get_ticket_counts.php.md)
- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiEffectiveCompanyScope()](/code/functions/apiEffectiveCompanyScope.md), [apiKeyCheckCompanies()](/code/functions/apiKeyCheckCompanies.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in `KnowledgeViewer::scopeForAllAccessible`
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleCompanies()](/code/functions/peopleCompanies.md)
- [includes/service_context.php](/code/files/includes/service_context.php.md) - in `ActorContext::sessionCompanyScope`
- [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) - in `AnalystsService::access`
- [includes/tenancy-switcher.php](/code/files/includes/tenancy-switcher.php.md) - in [renderTenantSwitcher()](/code/functions/renderTenantSwitcher.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getTenantConfigRowsByCompany()](/code/functions/getTenantConfigRowsByCompany.md), [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [analystHasAllTenantAccess()](/code/functions/analystHasAllTenantAccess.md)

