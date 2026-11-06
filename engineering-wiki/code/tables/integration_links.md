# integration_links

The ticket ↔ external issue spine. entity_type is polymorphic from day one (V1 only writes 'ticket'). status_category (todo|in_progress|done|cancelled) drives every decision; status_name is display only.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `connection_id` | `INT NOT NULL` |  |
| `entity_type` | `VARCHAR(20) NOT NULL DEFAULT 'ticket'` |  |
| `entity_id` | `INT NOT NULL` |  |
| `external_id` | `VARCHAR(100) NOT NULL` |  |
| `external_key` | `VARCHAR(100) NULL` |  |
| `external_url` | `VARCHAR(1000) NULL` |  |
| `status_name` | `VARCHAR(100) NULL` |  |
| `status_category` | `VARCHAR(20) NULL` |  |
| `assignee_name` | `VARCHAR(255) NULL` |  |
| `last_synced_datetime` | `DATETIME NULL` |  |
| `last_error` | `VARCHAR(500) NULL` |  |
| `created_by` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [cron/integration_poll.php](/code/files/cron/integration_poll.php.md):150 |  | update | `last_error` |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):642 | `integrationsEscalate` | insert | `connection_id`, `entity_type`, `entity_id`, `external_id`, `external_key`, `external_url`, `status_name`, `status_category`, `assignee_name`, `last_synced_datetime` |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):679 | `integrationsEscalate` | update | `last_error` |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):1492 | `integrationsRefreshConnection` | update | `status_name`, `status_category`, `assignee_name`, `last_synced_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/integrations/delete_connection.php](/code/files/api/integrations/delete_connection.php.md) | L38 |  |
| [api/integrations/send_attachment.php](/code/files/api/integrations/send_attachment.php.md) | L47 |  |
| [api/tickets/get_notes.php](/code/files/api/tickets/get_notes.php.md) | L65 |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) | integrationsSchemaReady L45, integrationsLinksFor L481, integrationsAlreadyLinked L506, integrationsPullComments L1424, integrationsRefreshConnection L1473 |  |

