# project_ai_threads

The AI project assistant (3.3.0) - includes/projects/assistant_chat.php.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `project_id` | `INT NOT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `summary` | `MEDIUMTEXT NULL` |  |
| `summary_upto_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md):90 | `projectChatThread` | insert | `project_id`, `analyst_id`, `created_datetime`, `updated_datetime` |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md):431 | `projectChatTurn` | update | `updated_datetime` |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md):487 | `projectChatSummarise` | update | `summary`, `summary_upto_id` |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md):661 | `projectChatClear` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) | projectChatReady L70, projectChatThread L85, projectChatThread L86, projectChatSummarise L465 |  |

