# system/modules/index.php

> PHP - 311 lines

System - Module Access Management (issue #30) Team- and analyst-level module access: a module-centric summary, a per-module edit modal, the most/least conflict-policy toggle, and an effective-access tool. Backed by api/system/get_module_access.php + save_module_grants.php + save_module_permission_mode.php + get_effective_module_access.php.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

