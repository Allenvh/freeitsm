# checklists/settings/index.php

> PHP - 445 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Checklists and SOPs Developer Guide](https://github.com/edmozley/freeitsm/wiki/Checklists-and-SOPs-Developer-Guide)

Checklists settings - categories, suggested roles and the left-panel preference.

Categories and roles are changed through api/checklists/save_lookup.php and
delete_lookup.php; only the sidebar mode posts back to this page (saved as
user_preferences.checklists_sidebar_mode).

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | **insert** [user_preferences](/code/tables/user_preferences.md) (`analyst_id`, `preference_key`, `preference_value`) |  |
| 54 |  | read [user_preferences](/code/tables/user_preferences.md) |  |
| 66 |  | read [checklist_categories](/code/tables/checklist_categories.md), read [checklist_templates](/code/tables/checklist_templates.md) |  |
| 76 |  | read [checklist_roles](/code/tables/checklist_roles.md), read [checklist_template_items](/code/tables/checklist_template_items.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [checklists/includes/header.php](/code/files/checklists/includes/header.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

