# morning-checks/index.php

> PHP - 1468 lines

Morning Checks Dashboard

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 51 |  | read [user_preferences](/code/tables/user_preferences.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [morning-checks/includes/header.php](/code/files/morning-checks/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/vendor/chart.umd.min.js](/code/files/assets/js/vendor/chart.umd.min.js.md), [assets/js/vendor/jspdf.umd.min.js](/code/files/assets/js/vendor/jspdf.umd.min.js.md), [assets/js/vendor/jspdf.plugin.autotable.min.js](/code/files/assets/js/vendor/jspdf.plugin.autotable.min.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

