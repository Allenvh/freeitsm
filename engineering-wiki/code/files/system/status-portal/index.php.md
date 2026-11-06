# system/status-portal/index.php

> PHP - 235 lines

System — Service status on the portal (discussion #99).

What end users see about outages: whether incidents appear on the self-service
portal at all, and how far back.

🔴 The switch is OFF until somebody turns it on here, and that is the point of
having a screen. Turning it on publishes incident TITLES and every update
marked external. Titles were written when nobody expected a customer to read
them, so this has to be a decision somebody makes with their eyes open rather
than something an upgrade does for them.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

