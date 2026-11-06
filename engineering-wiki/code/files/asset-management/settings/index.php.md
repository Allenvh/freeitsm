# asset-management/settings/index.php

> PHP - 4840 lines

Asset Management - Settings

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [cmdb_icons](/code/tables/cmdb_icons.md) |  |
| 55 |  | read [tenants](/code/tables/tenants.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [includes/handover_styles.php](/code/files/includes/handover_styles.php.md), [asset-management/includes/header.php](/code/files/asset-management/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/chart.min.js](/code/files/assets/js/chart.min.js.md), [assets/js/qrcode.min.js](/code/files/assets/js/qrcode.min.js.md), [assets/js/proxmox-settings.js](/code/files/assets/js/proxmox-settings.js.md), [assets/js/vcloud-settings.js](/code/files/assets/js/vcloud-settings.js.md), [assets/js/network-mapper-icons.js](/code/files/assets/js/network-mapper-icons.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [handoverDocumentCss()](/code/functions/handoverDocumentCss.md), [pageTitle()](/code/functions/pageTitle.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [settingsFirstTabId()](/code/functions/settingsFirstTabId.md), [settingsManifestFor()](/code/functions/settingsManifestFor.md), [settingsTabVisible()](/code/functions/settingsTabVisible.md), [settingsVisibleTabs()](/code/functions/settingsVisibleTabs.md), [t()](/code/functions/t.md)

