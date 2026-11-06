# domains/accounts/index.php

> PHP - 161 lines

Domains → Accounts (#154): the logins at registrars that hold the domains.

The question this page answers is the one asked at 11pm when a domain has
lapsed: "which account is it in, who has the login, and who has the phone
with the second factor on it?" It stores that — never the password itself,
which belongs in the organisation's password manager.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [domains/includes/header.php](/code/files/domains/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/domains.js](/code/files/assets/js/domains.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [pageTitle()](/code/functions/pageTitle.md), [requireModuleAccess()](/code/functions/requireModuleAccess.md), [t()](/code/functions/t.md)

