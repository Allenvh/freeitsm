# problem-management/settings/index.php

> PHP - 186 lines

Problem Management -> Settings: the Statuses, Priorities and Problem AI tabs.

Needs the problems module; each tab is shown only to analysts with its
capability (settings_manifest.php). Statuses and priorities are listed, added,
edited and deleted through api/problem-management/; the AI tab is the shared
AI provider panel for "Draft root cause" and "Detect problems".

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ai_settings_panel.php](/code/files/includes/ai_settings_panel.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [problem-management/includes/header.php](/code/files/problem-management/includes/header.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [renderAiSettingsPanel()](/code/functions/renderAiSettingsPanel.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [settingsFirstTabId()](/code/functions/settingsFirstTabId.md), [settingsManifestFor()](/code/functions/settingsManifestFor.md), [settingsTabVisible()](/code/functions/settingsTabVisible.md), [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md)

