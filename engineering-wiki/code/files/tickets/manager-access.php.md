# tickets/manager-access.php

> PHP - 52 lines

Manager access, opened from Tickets -> Users (discussion #62). The page itself is includes/manager_access_page.php, shared with the other module; this file only gives it this module's header and way back.

?user_id=N              the manager
?user_id=N&from=system  opened from System -> Managers, so Back goes there

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/manager_access_page.php](/code/files/includes/manager_access_page.php.md), [tickets/includes/header.php](/code/files/tickets/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [managerAccessRender()](/code/functions/managerAccessRender.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

