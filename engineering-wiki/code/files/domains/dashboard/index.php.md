# domains/dashboard/index.php

> PHP - 147 lines

Domains → Dashboard (#154): the portfolio at a glance — what needs doing, the next twelve months of renewals and what they will cost, how well the domains are protected, and how email security stands across all of them. Numbers from api/domains/dashboard.php, over the same scoped register the list shows.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [domains/includes/header.php](/code/files/domains/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/vendor/chart.umd.min.js](/code/files/assets/js/vendor/chart.umd.min.js.md), [assets/js/domains.js](/code/files/assets/js/domains.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

