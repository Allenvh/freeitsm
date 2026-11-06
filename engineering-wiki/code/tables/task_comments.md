# task_comments

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `task_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NOT NULL` |  |
| `comment` | `LONGTEXT NOT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md):559 | `TasksService::deleteTask` | delete |  |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md):588 | `TasksService::createComment` | insert | `task_id`, `analyst_id`, `comment`, `created_datetime` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L125 |  |
| [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) | apiTasksGet L362, apiTaskCommentsList L429 |  |

