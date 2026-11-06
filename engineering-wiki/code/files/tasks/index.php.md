# tasks/index.php

> PHP - 351 lines

Tasks Module — Kanban Board & List View

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | read [user_preferences](/code/tables/user_preferences.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [tasks/includes/header.php](/code/files/tasks/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/record-preview.js](/code/files/assets/js/record-preview.js.md), [assets/js/tinymce/tinymce.min.js](/code/files/assets/js/tinymce/tinymce.min.js.md), [assets/js/tasks-priority.js](/code/files/assets/js/tasks-priority.js.md), [assets/js/tasks-ctx-menu.js](/code/files/assets/js/tasks-ctx-menu.js.md), [assets/js/tasks.js](/code/files/assets/js/tasks.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAnalystAllowedModules()](/code/functions/getAnalystAllowedModules.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

