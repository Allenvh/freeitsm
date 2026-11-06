# tickets/settings/index.php

> PHP - 9094 lines

Admin Settings - Manage Departments, Ticket Types, and Exchange Integration

## Functions

| Function | Line | What it does |
|---|--:|---|
| [renderOversizeFields()](/code/functions/renderOversizeFields.md) | 76 | The oversized-email fields (GH #170), rendered twice: the install defaults on the Mailboxes tab (prefix 'ovd') and a mailbox's own rules in its dialog (prefix 'ovm', which also carries the two limits). files: `oversizeFields()`/ `setOversizeFields()` in the script read and fill them by prefix. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/ai_settings_panel.php](/code/files/includes/ai_settings_panel.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md), [includes/inbox_display.php](/code/files/includes/inbox_display.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [tickets/includes/header.php](/code/files/tickets/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/ai-settings.js](/code/files/assets/js/ai-settings.js.md), [assets/js/tinymce/tinymce.min.js](/code/files/assets/js/tinymce/tinymce.min.js.md), [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [inboxDisplayForAnalyst()](/code/functions/inboxDisplayForAnalyst.md), [inboxDisplayIsPersonal()](/code/functions/inboxDisplayIsPersonal.md), [inboxDisplayRegistry()](/code/functions/inboxDisplayRegistry.md), [pageTitle()](/code/functions/pageTitle.md), [renderAiSettingsPanel()](/code/functions/renderAiSettingsPanel.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [settingsFirstTabId()](/code/functions/settingsFirstTabId.md), [settingsManifestFor()](/code/functions/settingsManifestFor.md), [settingsTabVisible()](/code/functions/settingsTabVisible.md), [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md), [t()](/code/functions/t.md), [ticketChecklistClosureMode()](/code/functions/ticketChecklistClosureMode.md), [ticketChecklistEmptyClosureMode()](/code/functions/ticketChecklistEmptyClosureMode.md)

