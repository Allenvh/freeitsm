# system/debug-tools/index.php

> PHP - 172 lines

System - Debug Tools landing.

A searchable catalogue of diagnostics (same live-filter pattern as the System
landing). Each card opens that tool's own page at <slug>/index.php. The list
is driven by the registry in includes/tools.php.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [system/debug-tools/includes/tools.php](/code/files/system/debug-tools/includes/tools.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [debugToolIcon()](/code/functions/debugToolIcon.md), [getDebugTools()](/code/functions/getDebugTools.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

