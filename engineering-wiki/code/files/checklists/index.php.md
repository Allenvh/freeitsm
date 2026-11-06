# checklists/index.php

> PHP - 346 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Checklists and SOPs Developer Guide](https://github.com/edmozley/freeitsm/wiki/Checklists-and-SOPs-Developer-Guide)

Checklists - the template library page: cards, filters, search, and the template editor.

Loads every template, its steps and the categories for the page; deletes go through
checklists/api.php.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 30 |  | read [checklist_template_items](/code/tables/checklist_template_items.md) |  |
| 40 |  | read [checklist_categories](/code/tables/checklist_categories.md) |  |
| 52 |  | read [checklist_roles](/code/tables/checklist_roles.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [checklists/includes/header.php](/code/files/checklists/includes/header.php.md)

**Loads scripts:** [checklists/search_scoring.js](/code/files/checklists/search_scoring.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

