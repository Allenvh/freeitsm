# domains/view.php

> PHP - 195 lines

Domains — one domain (#154): everything known about it, what is wrong with it and how to fix it, what it looks like to the outside world, and every change ever made to it. Behaviour in assets/js/domains-view.js.

⚠️ Reads ONLY ?id= — the value entityLink('domain', …) produces, so the
notification bell, search, the recent trail and alert e-mails all land here.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 |  | read [domains](/code/tables/domains.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [domains/includes/header.php](/code/files/domains/includes/header.php.md), [includes/documents_panel.php](/code/files/includes/documents_panel.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/domains.js](/code/files/assets/js/domains.js.md), [assets/js/domains-view.js](/code/files/assets/js/domains-view.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystCanAccessDomain()](/code/functions/analystCanAccessDomain.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [entityVisit()](/code/functions/entityVisit.md), [pageTitle()](/code/functions/pageTitle.md), [renderDocumentsPanel()](/code/functions/renderDocumentsPanel.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

