# forms/fill.php

> PHP - 1013 lines

Forms Module - Fill In a Form

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [forms/includes/header.php](/code/files/forms/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/form-logic.js](/code/files/assets/js/form-logic.js.md), [assets/js/form-render.js](/code/files/assets/js/form-render.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

