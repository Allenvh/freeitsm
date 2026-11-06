# domains/index.php

> PHP - 209 lines

Domains — the register (#154).

Every domain the analyst's company holds (or every company they can see, in
the "All companies" view), with how long each has left, how well protected it
is, and a sidebar of the questions people actually ask: what needs attention,
what expires this month, what is unlocked. Behaviour is in
assets/js/domains-register.js; shared rendering in assets/js/domains.js.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [domains/includes/header.php](/code/files/domains/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/domains.js](/code/files/assets/js/domains.js.md), [assets/js/domains-register.js](/code/files/assets/js/domains-register.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

