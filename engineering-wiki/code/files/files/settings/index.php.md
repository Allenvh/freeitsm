# files/settings/index.php

> PHP - 122 lines

Files → Settings.

Two tabs, two capabilities (files/settings/manifest.php). Only the tabs this
analyst may use are rendered at all; api/files/settings.php checks the same
capability again for every read and write.

  Folders  every folder that exists - including ones the viewer cannot open -
           with Take ownership, the way back into an orphaned folder.
  Storage  where the bytes live on the server, and the largest upload.

Behaviour is in assets/js/files-settings.js.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [files/includes/header.php](/code/files/files/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/files-settings.js](/code/files/assets/js/files-settings.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [settingsFirstTabId()](/code/functions/settingsFirstTabId.md), [settingsManifestFor()](/code/functions/settingsManifestFor.md), [settingsTabVisible()](/code/functions/settingsTabVisible.md), [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md), [t()](/code/functions/t.md)

