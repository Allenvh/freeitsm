# process-mapper/settings/index.php

> PHP - 492 lines

Process Mapper — Settings

Manages the `process_step_types` palette (name + shape + colour). The
editor pulls this same list at page-load time so the toolbar, the detail-
panel Type dropdown and the right-click "Create new" submenu all reflect
whatever the user has configured here. Built-in types are protected from
deletion; deactivating a type hides it from the toolbar/context menu but
keeps it in the detail-panel dropdown (marked with a `*`) so existing
steps with that stored type are still selectable.

Visually aligned with the tickets settings page — same shared inbox.css
primitives (.tab-content card, .section-header, .add-btn, .table-action-btn,
.status-badge, .modal) so the two settings pages feel like one product.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [process-mapper/includes/shapes.php](/code/files/process-mapper/includes/shapes.php.md), [process-mapper/includes/header.php](/code/files/process-mapper/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [settingsFirstTabId()](/code/functions/settingsFirstTabId.md), [settingsManifestFor()](/code/functions/settingsManifestFor.md), [settingsTabVisible()](/code/functions/settingsTabVisible.md), [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md), [t()](/code/functions/t.md)

