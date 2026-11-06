# system/debug-tools/includes/tool-page.php

> PHP - 275 lines

Shared renderer for a single Debug Tool page. A per-tool page is just:

<?php `$DEBUG_TOOL_SLUG` = 'd001'; require __DIR__ . '/../includes/tool-page.php';

Included at top-level scope (NOT inside a function) so the header/waffle-menu
globals resolve correctly. Looks the tool up in the registry, renders its
detail + run UI, and wires the run/copy behaviour against its API script.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [system/debug-tools/includes/tools.php](/code/files/system/debug-tools/includes/tools.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/version.php](/code/files/includes/version.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Included by:** [system/debug-tools/d001/index.php](/code/files/system/debug-tools/d001/index.php.md), [system/debug-tools/d002/index.php](/code/files/system/debug-tools/d002/index.php.md), [system/debug-tools/d003/index.php](/code/files/system/debug-tools/d003/index.php.md), [system/debug-tools/d004/index.php](/code/files/system/debug-tools/d004/index.php.md), [system/debug-tools/d005/index.php](/code/files/system/debug-tools/d005/index.php.md), [system/debug-tools/d006/index.php](/code/files/system/debug-tools/d006/index.php.md), [system/debug-tools/d007/index.php](/code/files/system/debug-tools/d007/index.php.md), [system/debug-tools/d008/index.php](/code/files/system/debug-tools/d008/index.php.md), [system/debug-tools/d009/index.php](/code/files/system/debug-tools/d009/index.php.md), [system/debug-tools/d010/index.php](/code/files/system/debug-tools/d010/index.php.md), [system/debug-tools/d011/index.php](/code/files/system/debug-tools/d011/index.php.md), [system/debug-tools/d012/index.php](/code/files/system/debug-tools/d012/index.php.md), [system/debug-tools/d013/index.php](/code/files/system/debug-tools/d013/index.php.md), [system/debug-tools/d014/index.php](/code/files/system/debug-tools/d014/index.php.md), [system/debug-tools/d015/index.php](/code/files/system/debug-tools/d015/index.php.md), [system/debug-tools/d016/index.php](/code/files/system/debug-tools/d016/index.php.md), [system/debug-tools/d017/index.php](/code/files/system/debug-tools/d017/index.php.md), [system/debug-tools/d018/index.php](/code/files/system/debug-tools/d018/index.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [freeitsmVersion()](/code/functions/freeitsmVersion.md), [getDebugToolBySlug()](/code/functions/getDebugToolBySlug.md), [pageTitle()](/code/functions/pageTitle.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md), [t()](/code/functions/t.md)

