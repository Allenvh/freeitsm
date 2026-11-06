# system/integrations/tika.php

> PHP - 236 lines

System → Integrations → Apache Tika (discussion #53, tier 2).

Points FreeITSM at a document-reading service so that PDFs, older Office
formats and scanned documents become searchable. Everything the built-in tier
already reads — Word, Excel, PowerPoint, plain text — keeps working without
this and is unaffected by anything on this page.

⚠️ FREEITSM HOSTS NOTHING. This is an address and a timeout. Starting,
stopping and updating the service is the administrator's business, which is
why the page explains how to run one rather than offering to do it.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [integrationsProviderMeta()](/code/functions/integrationsProviderMeta.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

