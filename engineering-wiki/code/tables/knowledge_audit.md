# knowledge_audit

── Audit ─────────────────────────────────────────────────────────────── Modelled on document_access_log, which already does this job for attached documents. ⚠️ VIEWS ARE A DIFFERENT VOLUME CLASS FROM EDITS: view_count already increments on every read, and a row per view on a busy KB is millions a year. Creates, edits, permission changes, deletes and administrator-floor passes are rare and are what somebody actually comes looking for — do not let view spam bury them.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `object_type` | `VARCHAR(10) NOT NULL` | 'folder' \| 'article' |
| `object_id` | `INT NOT NULL` |  |
| `action` | `VARCHAR(20) NOT NULL` | create \| edit \| view \| delete \| restore \| move \| permissions \| admin_override |
| `analyst_id` | `INT NULL` |  |
| `user_id` | `INT NULL` |  |
| `detail` | `LONGTEXT NULL` | JSON. For a permission change: what the rows were and what they became. |
| `ip_address` | `VARCHAR(45) NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md):61 | `knowledgeAuditLog` | insert | `object_type`, `object_id`, `action`, `analyst_id`, `user_id`, `detail`, `ip_address` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md) | knowledgeAuditView L92, knowledgeAuditHistory L122 |  |

