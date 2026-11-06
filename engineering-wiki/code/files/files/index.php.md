# files/index.php

> PHP - 126 lines

Files — the desktop.

A Windows-like desktop for sharing files securely: desktop icons, a taskbar
with live window previews, a start menu, and Explorer windows (two panes,
icons/details views, a filter box in each). Everything is drawn by
assets/js/files-wm.js (windows, taskbar, menus) and assets/js/files-desktop.js
(Explorer, uploads, permissions, the apps). The server side is api/files/;
who-may-do-what is includes/files/acl.php and nowhere else.

Phones get no desktop - a plain list of the same folders (LAYER 45 in
mobile.css), because a desktop metaphor at 390px is a worse file manager.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/files/storage.php](/code/files/includes/files/storage.php.md), [files/includes/header.php](/code/files/files/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/files-icons.js](/code/files/assets/js/files-icons.js.md), [assets/js/files-wm.js](/code/files/assets/js/files-wm.js.md), [assets/js/files-viewer.js](/code/files/assets/js/files-viewer.js.md), [assets/js/files-desktop.js](/code/files/assets/js/files-desktop.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystHasCapability()](/code/functions/analystHasCapability.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [filesDesktopLogo()](/code/functions/filesDesktopLogo.md), [filesGetPrefs()](/code/functions/filesGetPrefs.md), [filesMaxUploadBytes()](/code/functions/filesMaxUploadBytes.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md), [t()](/code/functions/t.md)

