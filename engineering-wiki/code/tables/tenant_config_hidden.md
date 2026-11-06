# tenant_config_hidden

Multi-tenancy: the per-company "hide" layer for global config (the add+hide override model — design §7). A row means "this company does NOT want global <entity_type> #<entity_id> in its lists". Generic so one table serves every overridable config type (ticket_type, ticket_origin, department, …). The global row itself is never touched, so history/closed tickets still resolve it — hiding only removes it from that company's pickers, and is reversible.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `tenant_id` | `INT NOT NULL` |  |
| `entity_type` | `VARCHAR(50) NOT NULL` |  |
| `entity_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/assets/set_asset_status_type_hidden.php](/code/files/api/assets/set_asset_status_type_hidden.php.md):61 |  | insert | `tenant_id`, `entity_type`, `entity_id` |  |
| [api/assets/set_asset_status_type_hidden.php](/code/files/api/assets/set_asset_status_type_hidden.php.md):64 |  | delete |  |  |
| [api/assets/set_asset_type_hidden.php](/code/files/api/assets/set_asset_type_hidden.php.md):70 |  | insert | `tenant_id`, `entity_type`, `entity_id` |  |
| [api/assets/set_asset_type_hidden.php](/code/files/api/assets/set_asset_type_hidden.php.md):73 |  | delete |  |  |
| [api/tickets/set_ticket_origin_hidden.php](/code/files/api/tickets/set_ticket_origin_hidden.php.md):58 |  | insert | `tenant_id`, `entity_type`, `entity_id` |  |
| [api/tickets/set_ticket_origin_hidden.php](/code/files/api/tickets/set_ticket_origin_hidden.php.md):61 |  | delete |  |  |
| [api/tickets/set_ticket_type_hidden.php](/code/files/api/tickets/set_ticket_type_hidden.php.md):70 |  | insert | `tenant_id`, `entity_type`, `entity_id` |  |
| [api/tickets/set_ticket_type_hidden.php](/code/files/api/tickets/set_ticket_type_hidden.php.md):73 |  | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/assets/get_asset_status_types.php](/code/files/api/assets/get_asset_status_types.php.md) | L52 |  |
| [api/assets/get_asset_types.php](/code/files/api/assets/get_asset_types.php.md) | L78 |  |
| [api/assets/save_asset_status_type.php](/code/files/api/assets/save_asset_status_type.php.md) | L53 |  |
| [api/assets/save_asset_type.php](/code/files/api/assets/save_asset_type.php.md) | L62 |  |
| [api/tickets/get_ticket_origins.php](/code/files/api/tickets/get_ticket_origins.php.md) | L70 |  |
| [api/tickets/get_ticket_types.php](/code/files/api/tickets/get_ticket_types.php.md) | L82 |  |
| [api/tickets/save_ticket_origin.php](/code/files/api/tickets/save_ticket_origin.php.md) | L56 |  |
| [api/tickets/save_ticket_type.php](/code/files/api/tickets/save_ticket_type.php.md) | L59 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | getTenantConfigRows L1403 |  |

