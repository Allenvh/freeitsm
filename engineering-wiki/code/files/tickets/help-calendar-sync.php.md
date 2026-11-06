# tickets/help-calendar-sync.php

> PHP - 545 lines

Tickets — Calendar Sync Guide Standalone deep-dive linked from the main tickets help page (Calendar section). Covers the three routes out (subscription link, Microsoft 365, CalDAV), the admin connection, what each analyst turns on for themselves, changes coming back in, the cron-vs-notifications choice, and troubleshooting.

English only, like the other deep-dive help pages beside it.

⚠️ No `body { --accent: var(--accent-accent) }` block. The three help pages
next to this one all carry it and it has never done anything — --accent-accent
is defined nowhere in the product, so the declaration is invalid and --accent
falls back to the theme's own value. Copying it here would only spread dead
code; omitting it renders identically.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [tickets/includes/header.php](/code/files/tickets/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

