# service-status/index.php

> PHP - 1591 lines

Service Status Module - Dashboard Shows service board with worst current impact + recent incidents

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [service-status/includes/header.php](/code/files/service-status/includes/header.php.md), [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [ssPortalUpdatesEnabled()](/code/functions/ssPortalUpdatesEnabled.md), [t()](/code/functions/t.md)

