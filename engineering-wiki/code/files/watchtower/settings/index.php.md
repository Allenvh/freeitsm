# watchtower/settings/index.php

> PHP - 358 lines

Watchtower Settings — which cards appear, and what each count is counting.

Everything here TRIMS a dashboard that is already correct. Leave the whole
screen alone and Watchtower behaves exactly as it does with no rows in either
table: every card drawn, every status counted. That is deliberate — if ticking
boxes were what made the numbers right, every installation would be wrong
until somebody found this page, and an empty dashboard reads as "nothing needs
attention", which is the worst thing it could say untruthfully.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [includes/module-colors.php](/code/files/includes/module-colors.php.md), [watchtower/includes/header.php](/code/files/watchtower/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getModuleColors()](/code/functions/getModuleColors.md), [pageTitle()](/code/functions/pageTitle.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [settingsFirstTabId()](/code/functions/settingsFirstTabId.md), [settingsManifestFor()](/code/functions/settingsManifestFor.md), [settingsTabVisible()](/code/functions/settingsTabVisible.md), [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md), [t()](/code/functions/t.md)

