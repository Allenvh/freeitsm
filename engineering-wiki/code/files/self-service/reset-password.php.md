# self-service/reset-password.php

> PHP - 183 lines

Self-Service Portal — set a password from an emailed link (GH #134).

⚠️ The token is NOT validated here. This page only decides whether to draw a
form; api/self-service/reset_password.php is what accepts or refuses it, in one
statement, at the moment it is spent. Checking here as well would mean two
places that can disagree about what a valid token is, and the page is the half
an attacker can see. All this does is refuse to render a form for something
that is not even token-shaped.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md)

**Calls:** [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [t()](/code/functions/t.md)

