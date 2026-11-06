# getAllTenants()

All tenants as rows [id, name, slug, is_default, is_active].

```php
function getAllTenants(PDO $conn, bool $activeOnly = false): array
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 154.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$activeOnly` | `bool` | `false` | only return tenants with is_active = 1 |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 156 | `getAllTenants` | read [tenants](/code/tables/tenants.md) |  |

## Calls

[tenancyTablesReady()](/code/functions/tenancyTablesReady.md)

## Called from

- [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md)
- [api/contracts/customer_lookup.php](/code/files/api/contracts/customer_lookup.php.md)
- [api/integrations/list_connections.php](/code/files/api/integrations/list_connections.php.md)
- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [api/reporting/packs/catalogue.php](/code/files/api/reporting/packs/catalogue.php.md)
- [api/system/api_keys/list_keys.php](/code/files/api/system/api_keys/list_keys.php.md)
- [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md)
- [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md)
- [api/tickets/get_time_tracking_settings.php](/code/files/api/tickets/get_time_tracking_settings.php.md)
- [api/v1/resources/reference.php](/code/files/api/v1/resources/reference.php.md) - in [apiCompaniesList()](/code/functions/apiCompaniesList.md)
- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainApiLookups()](/code/functions/domainApiLookups.md)
- [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md) - in [rpTenantLabel()](/code/functions/rpTenantLabel.md)
- [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) - in `AnalystsService::companyIds`
- [includes/services/cost_centres.php](/code/files/includes/services/cost_centres.php.md) - in `CostCentresService::companiesFor`
- [includes/tenancy-switcher.php](/code/files/includes/tenancy-switcher.php.md) - in [renderTenantSwitcher()](/code/functions/renderTenantSwitcher.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [analystHasAllTenantAccess()](/code/functions/analystHasAllTenantAccess.md)
- [system/integrations/provider.php](/code/files/system/integrations/provider.php.md)
- [system/integrations/slack.php](/code/files/system/integrations/slack.php.md)

