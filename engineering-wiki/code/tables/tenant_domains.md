# tenant_domains

Domains owned by a tenant (used by shared-intake email routing).

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NOT NULL` |  |
| `domain` | `VARCHAR(255) NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/add_tenant_domain.php](/code/files/api/system/add_tenant_domain.php.md):74 |  | insert | `tenant_id`, `domain` |  |
| [api/system/delete_tenant_domain.php](/code/files/api/system/delete_tenant_domain.php.md):31 |  | delete |  |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md):94 |  | insert | `tenant_id`, `domain` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/add_freemail_domain.php](/code/files/api/system/add_freemail_domain.php.md) | L50 |  |
| [api/system/add_tenant_domain.php](/code/files/api/system/add_tenant_domain.php.md) | L60 |  |
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L576 |  |
| [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md) | L300 |  |
| [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md) | L107 |  |
| [api/system/get_tenant_domains.php](/code/files/api/system/get_tenant_domains.php.md) | L30 |  |
| [api/system/get_tenant_email_routing.php](/code/files/api/system/get_tenant_email_routing.php.md) | L60 |  |
| [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md) | L28 |  |
| [api/system/get_topology.php](/code/files/api/system/get_topology.php.md) | L62, L71 |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md) | L90 |  |
| [includes/people.php](/code/files/includes/people.php.md) | peopleCompanyCards L189, companyDetail L287 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | resolveTenantForNewUser L1070, resolveTicketTenantForEmail L1152, resolveTenantIdForAddress L1249 |  |

