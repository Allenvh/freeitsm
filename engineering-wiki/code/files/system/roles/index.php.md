# system/roles/index.php

> PHP - 205 lines

System — Roles (RBAC Layer 2).

Create roles that grant specific modules' SETTINGS to non-administrators, and
assign those roles to analysts and teams. System administrators don't need a
role — they bypass the whole layer — so this page is about delegating slices
of administration to people who aren't full admins. See docs/design/rbac.md.

Admin-only: the System header bounces non-admins, and every write API is
behind admin_api_guard.php.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [analysts](/code/tables/analysts.md) |  |
| 36 |  | read [teams](/code/tables/teams.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md), [assets/js/confirm.js](/code/files/assets/js/confirm.js.md), [assets/js/system-roles.js](/code/files/assets/js/system-roles.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [capGroups()](/code/functions/capGroups.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

