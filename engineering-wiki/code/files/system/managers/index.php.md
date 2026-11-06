# system/managers/index.php

> PHP - 312 lines

System — Managers (discussion #62, step 3).

The settings for portal managers - people in the self-service portal who may
see the tickets of the people they manage - and an overview of every manager.

What is NOT here: setting up one manager's lines. That is a full page of its
own (Manager access, opened from the person on Tickets -> Users or Assets ->
Users), because on a large organisation it means searching thousands of
people and hundreds of departments, and a settings page is the wrong place.

The rules live in includes/managers.php; api/system/managers.php reads and
writes the settings and builds the overview, a page at a time.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

