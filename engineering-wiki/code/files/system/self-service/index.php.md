# system/self-service/index.php

> PHP - 427 lines

System — Self-service portal.

How the portal LOOKS to customers, and what it LETS THEM DO. Both halves in
one place because an administrator setting up a portal is doing one job.

🔑 Why this is not part of System → Branding. Branding owns the one logo the
app, the login screen and the emails all share. The portal is the only screen
an organisation may want to brand differently - it is the face customers see,
and it is often the only part of FreeITSM they ever see. Putting "may a
customer close their own ticket?" on a screen about logos would be worse.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/self_service_settings.php](/code/files/includes/self_service_settings.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

