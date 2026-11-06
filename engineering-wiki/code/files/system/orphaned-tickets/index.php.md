# system/orphaned-tickets/index.php

> PHP - 232 lines

System - Orphaned tickets.

Lists tickets whose department_id points at a department that no longer
exists (so they're hidden from every team-filtered queue) and lets an admin
reassign them to a real department, one at a time or in bulk.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md)

