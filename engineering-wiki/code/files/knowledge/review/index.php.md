# knowledge/review/index.php

> PHP - 493 lines

Knowledge Base - Article Review Management

Pretty URL: /knowledge/review/  (moved from /knowledge/review.php in #395).
The page is a sibling to /knowledge/index.php — the "Edit" icon on each row
deep-links back to ../?article=N&edit=1 so the main page opens that article
straight in TinyMCE edit mode.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [knowledge/includes/header.php](/code/files/knowledge/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

