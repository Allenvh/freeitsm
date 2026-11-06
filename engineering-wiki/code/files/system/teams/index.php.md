# system/teams/index.php

> PHP - 644 lines

System - Teams

Team management, promoted out of Tickets → Settings so it lives alongside the
other cross-module concerns. Teams are consumed by tickets, tasks, contracts
and the workflow engine, so they belong at the install level rather than
buried in one module's settings.

This page owns team identity (name/description/order/active) and, via the
per-row "Manage departments" / "Manage members" pickers, the team side of the
department_teams and analyst_teams links. Those same links are also editable
from the other side (analyst→teams on System → Analysts, department→teams on
Tickets → Settings → Departments — departments are a ticket concept and stay
there); every side writes the same join tables.

Teams are global (no tenant scoping) by design, so no company filter is
needed even on a multi-company install. The endpoints still live under
api/tickets/ (shared with the Departments team-picker) — moving the UI
doesn't require moving the API.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 199 |  | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

