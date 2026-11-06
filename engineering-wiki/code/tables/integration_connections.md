# integration_connections

External issue trackers — see freeitsm.sql for the full commentary.  ⚠️ CONNECTION-shaped tenancy (same as messaging_channels): tenant_id NULL = SHARED across every company, set = PINNED to one. Never scope reads with `activeTenantFilter()`, which treats NULL as Default-owned.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(100) NOT NULL` |  |
| `provider` | `VARCHAR(20) NOT NULL DEFAULT 'jira'` |  |
| `base_url` | `VARCHAR(500) NOT NULL` |  |
| `auth_type` | `VARCHAR(20) NOT NULL DEFAULT 'api_token'` |  |
| `credentials` | `LONGTEXT NULL` |  |
| `webhook_secret` | `VARCHAR(2000) NULL` |  |
| `ingress_mode` | `VARCHAR(10) NOT NULL DEFAULT 'poll'` |  |
| `inbound_enabled` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `send_attachments` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `poll_interval_minutes` | `INT NOT NULL DEFAULT 5` |  |
| `account_identity` | `VARCHAR(255) NULL` |  |
| `tenant_id` | `INT NULL` |  |
| `is_active` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `last_poll_datetime` | `DATETIME NULL` |  |
| `last_poll_watermark` | `VARCHAR(100) NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/integrations/delete_connection.php](/code/files/api/integrations/delete_connection.php.md):52 |  | delete |  |  |
| [api/integrations/save_connection.php](/code/files/api/integrations/save_connection.php.md):118 |  | update | `name`, `base_url`, `credentials`, `tenant_id`, `is_active`, `inbound_enabled`, `send_attachments`, `account_identity` |  |
| [api/integrations/save_connection.php](/code/files/api/integrations/save_connection.php.md):128 |  | insert | `name`, `provider`, `base_url`, `auth_type`, `credentials`, `tenant_id`, `is_active`, `inbound_enabled`, `send_attachments`, `created_by` |  |
| [api/integrations/test_connection.php](/code/files/api/integrations/test_connection.php.md):76 |  | update | `account_identity`, `credentials` |  |
| [cron/integration_poll.php](/code/files/cron/integration_poll.php.md):157 |  | update | `last_poll_datetime` |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):1450 | `integrationsPullComments` | update | `last_poll_watermark` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_notes.php](/code/files/api/tickets/get_notes.php.md) | L65 |  |
| [cron/integration_poll.php](/code/files/cron/integration_poll.php.md) | L97 |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) | integrationsSchemaReady L44, integrationsLoadConnection L328, integrationsListConnections L368, integrationsLinksFor L481 |  |
| [system/integrations/index.php](/code/files/system/integrations/index.php.md) | L46 |  |

