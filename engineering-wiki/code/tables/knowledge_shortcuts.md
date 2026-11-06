# knowledge_shortcuts

── Shortcuts ─────────────────────────────────────────────────────────── A pointer with NO permissions of its own — it resolves to the target and the target's rules decide. That is what keeps the tree single-parent while still letting a document appear in two places. Deliberately has no polarity, no ACL, and no audience: a shortcut can never GRANT, and the reader must filter shortcuts by target readability at list time or the row leaks the target's title.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `folder_id` | `INT NULL` |  |
| `article_id` | `INT NOT NULL` |  |
| `created_by_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md):382 | `handleAddShortcut` | insert | `folder_id`, `article_id`, `created_by_id`, `created_datetime` |  |
| [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md):405 | `handleRemoveShortcut` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/knowledge/knowledge_articles.php](/code/files/api/knowledge/knowledge_articles.php.md) | L72 |  |

