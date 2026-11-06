# system/cost-centres/index.php

> PHP - 608 lines

System — Cost Centres (GH #160, stage 1).

The finance department's list of cost centres, per company: code, name,
description, parent (the hierarchy) and active/inactive. Added by hand, or
brought in from the accounting system with an Excel/CSV import or the REST
API (/cost-centres/sync) - all three through CostCentresService.

Stage 1 is the list itself. Charging assets and service bookings to a cost
centre is stage 2, which is why "inactive" already means "not offered for
new assignments" rather than "gone".

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md), [assets/js/confirm.js](/code/files/assets/js/confirm.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

