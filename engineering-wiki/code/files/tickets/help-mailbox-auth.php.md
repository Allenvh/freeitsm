# tickets/help-mailbox-auth.php

> PHP - 413 lines

Tickets — Mailbox Authentication Admin Guide Standalone deep-dive linked from the main tickets help page (Settings section). Covers the two Microsoft auth modes, the "reading from the right inbox" safeguards, email aliases, OAuth scopes/permissions, Azure setup, and troubleshooting.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [tickets/includes/header.php](/code/files/tickets/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

