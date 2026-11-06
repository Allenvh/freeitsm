# system/analysts/index.php

> PHP - 1166 lines

System - Analysts

Analyst account management, promoted out of Tickets → Settings. Analysts are
the app's users — they work across every module — so their accounts belong at
the install level, not inside one module's settings.

This page owns: analyst CRUD, password reset, single sign-on provider
assignment, per-analyst team membership, and (multi-company installs only)
per-company access grants. The endpoints stay under api/tickets/ — moving the
UI here doesn't require moving the API.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 290 |  | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

