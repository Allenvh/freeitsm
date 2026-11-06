# asset-management/leases/view.php

> PHP - 69 lines

Assets -> Leases (3.5.0): one lease - the agreement, its schedules, the assets on each and what became of them, and its documents. Behaviour in assets/js/leases.js; rules in includes/leases.php.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/leases.php](/code/files/includes/leases.php.md), [asset-management/includes/header.php](/code/files/asset-management/includes/header.php.md), [includes/documents_panel.php](/code/files/includes/documents_panel.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/leases.js](/code/files/assets/js/leases.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [leaseCanView()](/code/functions/leaseCanView.md), [leaseReady()](/code/functions/leaseReady.md), [pageTitle()](/code/functions/pageTitle.md), [renderDocumentsPanel()](/code/functions/renderDocumentsPanel.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

