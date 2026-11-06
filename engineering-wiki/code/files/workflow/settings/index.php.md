# workflow/settings/index.php

> PHP - 313 lines

Workflows — Settings page.

Single tab for now (AI integration); the tabs scaffold is in place so
further per-module settings (defaults, retention policy, etc.) can be
dropped in without re-architecting the page.

Visual chrome mirrors tickets/settings exactly (shared inbox.css
primitives + tab strip + form-group layout) so the page feels like the
rest of the product. AI key is encrypted at rest via the existing
`workflow_ai_api_key` entry in ENCRYPTED_SETTING_KEYS and returned to
the client as a "****<last4>" mask only.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/ai_settings_panel.php](/code/files/includes/ai_settings_panel.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [workflow/includes/header.php](/code/files/workflow/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/ai-settings.js](/code/files/assets/js/ai-settings.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [renderAiSettingsPanel()](/code/functions/renderAiSettingsPanel.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [settingsFirstTabId()](/code/functions/settingsFirstTabId.md), [settingsManifestFor()](/code/functions/settingsManifestFor.md), [settingsTabVisible()](/code/functions/settingsTabVisible.md), [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md), [t()](/code/functions/t.md)

