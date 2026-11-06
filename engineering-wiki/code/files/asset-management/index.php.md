# asset-management/index.php

> PHP - 4610 lines

Assets - View and manage IT assets and their user assignments

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/documents_panel.php](/code/files/includes/documents_panel.php.md), [asset-management/includes/header.php](/code/files/asset-management/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/record-preview.js](/code/files/assets/js/record-preview.js.md), [assets/js/network-mapper-icons.js](/code/files/assets/js/network-mapper-icons.js.md), [assets/js/project-links.js](/code/files/assets/js/project-links.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [documentsPanelAssets()](/code/functions/documentsPanelAssets.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

