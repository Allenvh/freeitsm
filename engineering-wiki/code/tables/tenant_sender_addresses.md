# tenant_sender_addresses

Specific sender addresses mapped to a company (shared-intake routing). The address-level twin of tenant_domains: matched before the domain, so a personal/freemail address (jane@gmail.com) can route to a company even though its domain is never mappable. UNIQUE so one address routes one way.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NOT NULL` |  |
| `email` | `VARCHAR(255) NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/add_tenant_sender.php](/code/files/api/system/add_tenant_sender.php.md):71 |  | insert | `tenant_id`, `email` |  |
| [api/system/delete_tenant_sender.php](/code/files/api/system/delete_tenant_sender.php.md):31 |  | delete |  |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md):110 |  | insert | `tenant_id`, `email` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/add_tenant_sender.php](/code/files/api/system/add_tenant_sender.php.md) | L57 |  |
| [api/system/debug-tools/D003_selfservice_sso.php](/code/files/api/system/debug-tools/D003_selfservice_sso.php.md) | L299 |  |
| [api/system/email_routing_test.php](/code/files/api/system/email_routing_test.php.md) | L96 |  |
| [api/system/get_tenant_email_routing.php](/code/files/api/system/get_tenant_email_routing.php.md) | L67 |  |
| [api/system/get_tenant_senders.php](/code/files/api/system/get_tenant_senders.php.md) | L31 |  |
| [api/system/get_topology.php](/code/files/api/system/get_topology.php.md) | L63 |  |
| [api/tickets/resolve_triage.php](/code/files/api/tickets/resolve_triage.php.md) | L106 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | resolveTicketTenantForEmail L1134, resolveTenantIdForAddress L1239 |  |

