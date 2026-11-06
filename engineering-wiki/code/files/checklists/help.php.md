# checklists/help.php

> PHP - 362 lines

Checklists & SOPs Help Guide - full page with left pane navigation.

⚠️ The module shipped in 2.0.0 with NO help page and no Help button, the only
one of 21 modules without either. Same house style as every other module's
guide (help.css, scroll-spy, numbered sections) - see the wiki page
"Help page house style".

Accent: the module has no --chk-* tokens in theme.css; its header hardcodes
the teal gradient. Set here from the same two colours rather than inventing
tokens for one page.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [checklists/includes/header.php](/code/files/checklists/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

