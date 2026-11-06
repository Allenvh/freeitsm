# team_tenant_access

Which companies a TEAM grants its members (only consulted when the team is NOT flagged can_access_all_tenants). Unioned with each member's own analyst_tenant_access in `getAccessibleTenantIds()`.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `team_id` | `INT NOT NULL` |  |
| `tenant_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/tickets/save_team_companies.php](/code/files/api/tickets/save_team_companies.php.md):39 |  | delete |  |  |
| [api/tickets/save_team_companies.php](/code/files/api/tickets/save_team_companies.php.md):41 |  | insert | `team_id`, `tenant_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_analysts.php](/code/files/api/tickets/get_analysts.php.md) | L49 |  |
| [api/tickets/get_team_companies.php](/code/files/api/tickets/get_team_companies.php.md) | L32 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | getAccessibleTenantIds L230 |  |

