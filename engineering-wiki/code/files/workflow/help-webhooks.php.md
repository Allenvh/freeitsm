# workflow/help-webhooks.php

> PHP - 352 lines

Workflows — Webhooks deep-dive help page.

A standalone, beginner-to-expert guide to outbound webhooks: what they are,
how they slot into the Workflows module, the four payload formats (incl. the
Full-record delighter), testing, HMAC signing, the async delivery engine, the
overview dashboard, the event catalogue, and troubleshooting.

Linked from workflow/help.php (Actions section + sidebar). English-first — the
i18n rollout backfills the other locales later, same as any new content.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [workflow/includes/header.php](/code/files/workflow/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

