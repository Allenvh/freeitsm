# task_tags

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `name` | `VARCHAR(50) NOT NULL` |  |
| `colour` | `VARCHAR(20) NULL` |  |
| `display_order` | `INT NOT NULL DEFAULT 0` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md):3409 |  | insert | `name`, `colour`, `display_order` |  |
| [api/tasks/delete_task_tag.php](/code/files/api/tasks/delete_task_tag.php.md):27 |  | delete |  |  |
| [api/tasks/save_task_tag.php](/code/files/api/tasks/save_task_tag.php.md):34 |  | update | `name`, `colour`, `display_order` |  |
| [api/tasks/save_task_tag.php](/code/files/api/tasks/save_task_tag.php.md):39 |  | insert | `name`, `colour`, `display_order` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/system/db_verify.php](/code/files/api/system/db_verify.php.md) | L3407 |  |
| [api/tasks/delete_task_tag.php](/code/files/api/tasks/delete_task_tag.php.md) | L26 |  |
| [api/tasks/get.php](/code/files/api/tasks/get.php.md) | L136 |  |
| [api/tasks/get_task_tags.php](/code/files/api/tasks/get_task_tags.php.md) | L19 |  |
| [api/tasks/list.php](/code/files/api/tasks/list.php.md) | L247 |  |
| [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) | apiSerializeTask L74, apiTasksList L231, apiTaskTagsList L492 |  |
| [includes/services/tasks.php](/code/files/includes/services/tasks.php.md) | TasksService::resolveTags L1447, TasksService::resolveTags L1450 |  |

