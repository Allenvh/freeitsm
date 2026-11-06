# system/security/index.php

> PHP - 464 lines

System - Security Settings Trusted device, password policy, and account lockout configuration

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md), [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [oversizeMbWords()](/code/functions/oversizeMbWords.md), [pageTitle()](/code/functions/pageTitle.md), [phpUploadLimits()](/code/functions/phpUploadLimits.md), [t()](/code/functions/t.md)

