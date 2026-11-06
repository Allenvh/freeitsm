# self-service/forgot-password.php

> PHP - 157 lines

Self-Service Portal — ask for a link to set a password (GH #134).

⚠️ NOT gated on `selfServiceRegistrationEnabled()`. That setting decides whether
strangers may create accounts; this page is for people who already HAVE one.
Gating it would recreate the reported bug exactly — a service desk that creates
its customers by hand keeps self-registration off, and those are precisely the
accounts with no password and no way to get one.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md)

**Calls:** [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [t()](/code/functions/t.md)

