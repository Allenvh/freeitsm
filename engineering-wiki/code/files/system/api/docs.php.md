# system/api/docs.php

> PHP - 711 lines

System - API documentation + interactive tester.

Documents every REST API v1 endpoint (data-driven from the SPEC array
below — keep it in step with api/v1/index.php's route table) and lets an
admin fire real requests against this install and inspect the response.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requestScheme()](/code/functions/requestScheme.md)

