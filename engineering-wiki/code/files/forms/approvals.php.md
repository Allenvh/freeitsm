# forms/approvals.php

> PHP - 329 lines

Catalogue-request approvals inbox (#928).

Where a designated approver signs off (or turns down) the catalogue requests waiting
on them. Approving raises the ticket; rejecting records the decision. Modelled on the
Change Management approvals inbox, scoped to the Forms module.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/version.php](/code/files/includes/version.php.md), [forms/includes/header.php](/code/files/forms/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/clipboard.js](/code/files/assets/js/clipboard.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [freeitsmVersion()](/code/functions/freeitsmVersion.md), [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

