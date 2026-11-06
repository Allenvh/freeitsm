# files/help.php

> PHP - 153 lines

Files — help. Numbered sections with a scroll-spy sidebar, the house layout from assets/css/help.css. The words live in lang/<locale>/files.php under help.*, one entry per paragraph, so every section is translatable and this file is only the shape.

?embed=1 is the same page without the header - the desktop's Help window
shows it in a frame.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [files/includes/header.php](/code/files/files/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

