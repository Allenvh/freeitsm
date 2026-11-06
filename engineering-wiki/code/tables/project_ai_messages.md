# project_ai_messages

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `thread_id` | `INT NOT NULL` |  |
| `role` | `VARCHAR(10) NOT NULL` |  |
| `kind` | `VARCHAR(10) NOT NULL DEFAULT 'chat'` |  |
| `content` | `MEDIUMTEXT NULL` |  |
| `proposals` | `MEDIUMTEXT NULL` |  |
| `looked_at` | `TEXT NULL` |  |
| `analyst_id` | `INT NULL` |  |
| `model` | `VARCHAR(120) NULL` |  |
| `tokens_in` | `INT NULL` |  |
| `tokens_out` | `INT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md):426 | `projectChatTurn` | insert | `thread_id`, `role`, `kind`, `content`, `proposals`, `looked_at`, `analyst_id`, `model`, `tokens_in`, `tokens_out` |  |
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md):530 | `projectChatApply` | update | `proposals` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) | projectChatReady L70, projectChatMessages L102, projectChatTurn L363, projectChatOpen L445, projectChatSummarise L469, projectChatApply L508 |  |

