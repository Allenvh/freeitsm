# system/sso/index.php

> PHP - 1548 lines

System - Single Sign-On (SSO / OIDC) Configure OpenID Connect identity providers + global SSO switches.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | read [tenants](/code/tables/tenants.md) |  |
| 34 |  | read [teams](/code/tables/teams.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [pageTitle()](/code/functions/pageTitle.md), [requestScheme()](/code/functions/requestScheme.md), [t()](/code/functions/t.md)

