# change_comments

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `change_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `comment_text` | `LONGTEXT NOT NULL` |  |
| `is_internal` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):423 | `ChangesService::createComment` | insert | `change_id`, `analyst_id`, `comment_text`, `is_internal`, `created_datetime` |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md):455 | `ChangesService::deleteComment` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/change-management/get.php](/code/files/api/change-management/get.php.md) | L77 |  |
| [api/change-management/get_comments.php](/code/files/api/change-management/get_comments.php.md) | L33 |  |
| [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) | apiChangeCommentsList L389 |  |
| [includes/services/changes.php](/code/files/includes/services/changes.php.md) | ChangesService::deleteComment L446 |  |

