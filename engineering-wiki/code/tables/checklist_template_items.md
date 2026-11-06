# checklist_template_items

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `template_id` | `INT NOT NULL` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `description` | `TEXT DEFAULT NULL` |  |
| `is_mandatory` | `TINYINT(1) NOT NULL DEFAULT 1` |  |
| `suggested_role` | `VARCHAR(100) DEFAULT NULL` |  |
| `sort_order` | `INT NOT NULL DEFAULT 1` |  |
| `requires_input` | `TINYINT(1) NOT NULL DEFAULT 0` |  |
| `input_placeholder` | `VARCHAR(255) DEFAULT NULL` |  |
| `default_value` | `VARCHAR(255) DEFAULT NULL` |  |
| `created_datetime` | `DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [checklists/api.php](/code/files/checklists/api.php.md):91 |  | delete |  |  |
| [checklists/api.php](/code/files/checklists/api.php.md):94 |  | insert | `template_id`, `title`, `suggested_role`, `is_mandatory`, `requires_input`, `input_placeholder`, `sort_order`, `is_demo` |  |
| [checklists/api.php](/code/files/checklists/api.php.md):118 |  | delete |  |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md):372 | `ChecklistsService::saveLookup` | update | `suggested_role` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md) | L255 |  |
| [checklists/api.php](/code/files/checklists/api.php.md) | L41 |  |
| [checklists/index.php](/code/files/checklists/index.php.md) | L30 |  |
| [checklists/settings/index.php](/code/files/checklists/settings/index.php.md) | L76 |  |
| [includes/services/checklists.php](/code/files/includes/services/checklists.php.md) | ChecklistsService::copyTemplateToTask L553 |  |
| [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) | WorkflowEngine::action_attach_checklist L2213 |  |

