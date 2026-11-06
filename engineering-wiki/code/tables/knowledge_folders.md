# knowledge_folders

── Knowledge folders ─────────────────────────────────────────────────── A document lives in EXACTLY ONE folder. That is the load-bearing decision: with several parents, "inherit from parent" has no answer — most-permissive leaks, most-restrictive loses documents people filed themselves. Appearing in two places is what knowledge_shortcuts is for.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `parent_id` | `INT NULL` | NULL = a top-level folder. The root itself is not a row. |
| `name` | `VARCHAR(255) NOT NULL` |  |
| `is_restricted` | `TINYINT(1) NOT NULL DEFAULT 0` | Same meaning as on an article — see above. |
| `inherit_permissions` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `owner_id` | `INT NULL` | Who to ask when a folder ends up unreachable. Not a permission by itself: recovery is the knowledge.admin floor, which always passes. |
| `tenant_id` | `INT NULL` | ⚠️ NULL = shared with every company, exactly as on knowledge_articles, and the OPPOSITE of tickets/assets. See includes/tenancy.php. |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `modified_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md):193 | `handleCreate` | insert | `parent_id`, `name`, `is_restricted`, `inherit_permissions`, `created_by_id`, `owner_id`, `created_datetime`, `modified_datetime` |  |
| [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md):215 | `handleRename` | update | `name`, `modified_datetime` |  |
| [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md):254 | `handleMove` | update | `parent_id`, `modified_datetime` |  |
| [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md):293 | `handleDelete` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) | handleList L80, requireFolderAccess L164, handleDelete L283, handleExceptions L433 |  |
| [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) | resolveInherited L179 |  |
| [api/self-service/get_knowledge_articles.php](/code/files/api/self-service/get_knowledge_articles.php.md) | L72 |  |
| [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) | knowledgeAclHasAnyRows L848, knowledgeFolderIndex L881 |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md) | KnowledgeService::resolveFolderId L95 |  |

