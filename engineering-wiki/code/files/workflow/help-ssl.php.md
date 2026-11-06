# workflow/help-ssl.php

> PHP - 300 lines

Workflows — HTTPS / SSL certificate verification help page.

Written because the failure it documents is (a) extremely common on a fresh
Windows/WAMP install, (b) not FreeITSM's fault, and (c) reported by cURL in
language that tells you what broke but not what to do:

    SSL certificate problem: unable to get local issuer certificate

`webhookDiagnoseError()` (includes/webhook_delivery.php) recognises that error
and deep-links straight here from the workflow editor's Send-test panel and
from the System > Webhooks delivery log.

English-first, same as help-webhooks.php — the i18n rollout backfills the
other locales later.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [workflow/includes/header.php](/code/files/workflow/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

