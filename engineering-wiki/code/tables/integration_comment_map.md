# integration_comment_map

Echo suppression + idempotency for comment sync. One row per comment that has crossed in either direction. direction is 'in' (tracker -> note) or 'out' (note -> tracker).  ⚠️ UNIQUE (link_id, external_comment_id) — declared in freeitsm.sql, not here — is the thing that actually guarantees a comment is imported once. The "have I seen this?" read is an optimisation; the key is the guarantee, and it is what makes two overlapping cron runs safe.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `link_id` | `INT NOT NULL` |  |
| `direction` | `VARCHAR(3) NOT NULL DEFAULT 'in'` |  |
| `external_comment_id` | `VARCHAR(100) NOT NULL` |  |
| `local_note_id` | `INT NULL` |  |
| `author_identity` | `VARCHAR(255) NULL` |  |
| `author_name` | `VARCHAR(255) NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):1291 | `integrationsRecordOutboundComment` | insert | `link_id`, `direction`, `external_comment_id`, `local_note_id`, `created_datetime` |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):1356 | `integrationsApplyCommentEvent` | insert | `link_id`, `direction`, `external_comment_id`, `author_identity`, `author_name`, `created_datetime` |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md):1379 | `integrationsApplyCommentEvent` | update | `local_note_id` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/get_notes.php](/code/files/api/tickets/get_notes.php.md) | L65 |  |
| [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) | integrationsCommentSchemaReady L1177, integrationsKnownCommentIds L1267 |  |

