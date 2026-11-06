# domains/settings/index.php

> PHP - 376 lines

Domains → Settings (#154).

Five tabs, five capabilities (domains/settings/manifest.php). Only the tabs
this analyst may use are rendered at all. Every value is read from and saved
to api/domains/settings.php, which validates it and checks the same
capability as its tab — the defaults shown here come from the server, so
the screen and the server cannot disagree about "not set yet".

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 183 |  | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [domains/includes/header.php](/code/files/domains/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/domains.js](/code/files/assets/js/domains.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [settingsFirstTabId()](/code/functions/settingsFirstTabId.md), [settingsManifestFor()](/code/functions/settingsManifestFor.md), [settingsTabVisible()](/code/functions/settingsTabVisible.md), [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md), [t()](/code/functions/t.md)

