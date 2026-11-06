# checklists/includes/header.php

> PHP - 131 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Checklists and SOPs Developer Guide](https://github.com/edmozley/freeitsm/wiki/Checklists-and-SOPs-Developer-Guide)

The Checklists module header - waffle menu, Templates / Settings / Help navigation and the teal styling.

Included by every Checklists page; expects `$current_page` and `$path_prefix`. Reads the
analyst's checklists_sidebar_mode preference into `$checklistsSidebarMode` ('always' or
'hover') for the left-panel CSS that follows.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 25 |  | read [user_preferences](/code/tables/user_preferences.md) |  |

## How it hangs together

**Included by:** [checklists/edit/index.php](/code/files/checklists/edit/index.php.md), [checklists/help.php](/code/files/checklists/help.php.md), [checklists/index.php](/code/files/checklists/index.php.md), [checklists/settings/index.php](/code/files/checklists/settings/index.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [renderHeaderRight()](/code/functions/renderHeaderRight.md), [renderWaffleMenuButton()](/code/functions/renderWaffleMenuButton.md), [renderWaffleMenuJS()](/code/functions/renderWaffleMenuJS.md), [renderWaffleMenuPanel()](/code/functions/renderWaffleMenuPanel.md), [t()](/code/functions/t.md)

