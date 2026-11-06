# system/email-routing-test/index.php

> PHP - 282 lines

System - Email routing test (dry-run diagnostic)

"If an email came from <X> to <mailbox>, where would it land?" Runs the real
inbound-routing function (api/system/email_routing_test.php) without creating
anything, and shows which rule decided the result. Only useful once a second
company exists; on a single-company install it simply explains that all mail
goes to the Default company.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

