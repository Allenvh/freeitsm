# tickets/help-email-templates.php

> PHP - 285 lines

Tickets — Automatic emails & signatures Admin Guide Standalone deep-dive linked from the main tickets help page (Settings section). Covers merge codes and [ticket_url], the public web address, limiting templates to particular senders, the simulator, and per-analyst signatures.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [tickets/includes/header.php](/code/files/tickets/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

