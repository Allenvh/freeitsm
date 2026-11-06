# war-room/settings/index.php

> PHP - 176 lines

War room settings — the two decisions an administrator owns.

Retention is applied on WRITE (see warRoomPrune), not by a scheduled job, so
there is nothing to configure beyond the dropdown and nothing that can be left
un-set-up on the day it matters.

The situation report is the one part of the module that needs the internet, so
the page says that in as many words. Leaving it unconfigured is a perfectly
good answer and the chat is unaffected either way.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/ai_settings_panel.php](/code/files/includes/ai_settings_panel.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [war-room/includes/header.php](/code/files/war-room/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/ai-settings.js](/code/files/assets/js/ai-settings.js.md), [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [renderAiSettingsPanel()](/code/functions/renderAiSettingsPanel.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [settingsFirstTabId()](/code/functions/settingsFirstTabId.md), [settingsManifestFor()](/code/functions/settingsManifestFor.md), [settingsTabVisible()](/code/functions/settingsTabVisible.md), [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md), [t()](/code/functions/t.md), [warRoomRetentionDays()](/code/functions/warRoomRetentionDays.md)

