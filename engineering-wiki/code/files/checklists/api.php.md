# checklists/api.php

> PHP - 134 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Checklists and SOPs Developer Guide](https://github.com/edmozley/freeitsm/wiki/Checklists-and-SOPs-Developer-Guide)

API: checklist templates - get one, save one, delete one, chosen by ?action=.

get returns a template with its steps; save writes the template and replaces all its
steps (they inherit the template's is_demo flag so demo removal takes both); delete
(POST only) removes the steps and then the template in one transaction.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 41 |  | read [checklist_template_items](/code/tables/checklist_template_items.md) |  |
| 67 |  | **update** [checklist_templates](/code/tables/checklist_templates.md) (`title`, `category`, `scope`, `closure_mode`, `description`, `keywords`) |  |
| 70 |  | **insert** [checklist_templates](/code/tables/checklist_templates.md) (`title`, `category`, `scope`, `closure_mode`, `description`, `keywords`, `created_by_id`) |  |
| 77 |  | **insert** [checklist_categories](/code/tables/checklist_categories.md) (`name`) |  |
| 89 |  | read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 91 |  | **delete** [checklist_template_items](/code/tables/checklist_template_items.md) |  |
| 94 |  | **insert** [checklist_template_items](/code/tables/checklist_template_items.md) (`template_id`, `title`, `suggested_role`, `is_mandatory`, `requires_input`, `input_placeholder`, `sort_order`, `is_demo`) |  |
| 118 |  | **delete** [checklist_template_items](/code/tables/checklist_template_items.md) |  |
| 119 |  | **delete** [checklist_templates](/code/tables/checklist_templates.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

