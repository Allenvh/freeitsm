# system/branding/index.php

> PHP - 1076 lines

System - Branding Settings

Organisation-wide branding: logo + default header/footer template slots.
These act as the fallback for any module that renders branded output
(currently Network Mapper's diagram header/footer; future PDF/PNG export
surfaces will read the same settings).

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [brandingLoginDesign()](/code/functions/brandingLoginDesign.md), [brandingLoginFields()](/code/functions/brandingLoginFields.md), [brandingLoginPresets()](/code/functions/brandingLoginPresets.md), [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [brandingScopes()](/code/functions/brandingScopes.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

