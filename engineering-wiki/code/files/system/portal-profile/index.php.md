# system/portal-profile/index.php

> PHP - 250 lines

System — Portal profile (#133).

What a person may change about themselves in the self-service portal, and
whether a customer from an address book may do so too, their change being
sent to the address book (GDPR Art. 16).

Only ever narrows USER_SELF_EDITABLE_FIELDS: manager, employee ID and
department are not offered at all, and the page says why.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

