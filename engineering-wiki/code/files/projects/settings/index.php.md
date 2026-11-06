# projects/settings/index.php

> PHP - 262 lines

Projects -> Settings (3.2.0).

Five tabs, five capabilities (projects/settings/manifest.php). Only the tabs
this analyst may use are rendered. Every value is read from and saved to
api/projects/settings.php, which validates it and checks the same capability
as its tab - the defaults shown come from the server, so the screen and the
server cannot disagree about "not set yet". Behaviour: assets/js/projects-settings.js.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 102 |  | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/ai_settings_panel.php](/code/files/includes/ai_settings_panel.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [projects/includes/header.php](/code/files/projects/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/projects.js](/code/files/assets/js/projects.js.md), [assets/js/projects-settings.js](/code/files/assets/js/projects-settings.js.md), [assets/js/ai-settings.js](/code/files/assets/js/ai-settings.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [renderAiSettingsPanel()](/code/functions/renderAiSettingsPanel.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [settingsFirstTabId()](/code/functions/settingsFirstTabId.md), [settingsManifestFor()](/code/functions/settingsManifestFor.md), [settingsTabVisible()](/code/functions/settingsTabVisible.md), [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md), [t()](/code/functions/t.md)

