# reporting/intune/index.php

> PHP - 767 lines

Reporting - Intune Dashboard

Curated dashboard of Intune device aggregations: KPI strip plus an
eight-widget chart grid covering compliance, OS, owner type,
manufacturer, OS version, enrolment trend, last sync, and encryption.

All data fetched from a single call to api/intune/dashboard_data.php.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [reporting/includes/header.php](/code/files/reporting/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/chart.min.js](/code/files/assets/js/chart.min.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

