# analyst_tenant_access

Which analysts may access which tenants (only consulted when an analyst is NOT flagged can_access_all_tenants).

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `tenant_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md):175 |  | delete |  |  |
| [api/tickets/save_analyst.php](/code/files/api/tickets/save_analyst.php.md):177 |  | insert | `analyst_id`, `tenant_id` |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md):229 | `AnalystsService::setAccess` | delete |  |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md):230 | `AnalystsService::setAccess` | insert | `analyst_id`, `tenant_id` |  |
| [includes/sso_identity.php](/code/files/includes/sso_identity.php.md):229 | `ssoApplyJitCompanies` | delete |  |  |
| [includes/sso_identity.php](/code/files/includes/sso_identity.php.md):232 | `ssoApplyJitCompanies` | insert | `analyst_id`, `tenant_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/get_topology.php](/code/files/api/system/get_topology.php.md) | L65 |  |
| [api/tickets/get_analysts.php](/code/files/api/tickets/get_analysts.php.md) | L33 |  |
| [includes/services/analysts.php](/code/files/includes/services/analysts.php.md) | AnalystsService::access L159 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | getAccessibleTenantIds L230, getAccessibleTenantIds L240 |  |

