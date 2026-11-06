# lms/settings/index.php

> PHP - 362 lines

LMS settings — currently just the AI provider used by the course editor.

Follows the tickets/settings shell (pinned header, scrolling full-width
container, shared inbox.css primitives) so it reads like every other module's
settings page rather than inventing its own.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ai_settings_panel.php](/code/files/includes/ai_settings_panel.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [lms/includes/header.php](/code/files/lms/includes/header.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [renderAiSettingsPanel()](/code/functions/renderAiSettingsPanel.md), [requireCapability()](/code/functions/requireCapability.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

