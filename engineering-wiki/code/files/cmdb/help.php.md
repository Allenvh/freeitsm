# cmdb/help.php

> PHP - 639 lines

CMDB Module Help Guide — full page with left pane navigation

Presentation comes from assets/css/help.css, the shared house style for
every module help guide. This page sets the CMDB accent and nothing else —
if you find yourself adding layout CSS here, it probably belongs in help.css
so every module gets it.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [cmdb/includes/header.php](/code/files/cmdb/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

