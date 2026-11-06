# system/sso/provider.php

> PHP - 1142 lines

System → Authentication → one LDAP / Active Directory provider.

A directory carries far too much configuration for a dialog: a connection, a
sign-in scope, group gating, an import scope, attribute mapping, safety
settings and a run history. It had all been bolted onto the modal on the list
page, where the import section ended up below the fold and people reasonably
concluded it was not there.

OIDC providers keep the modal — an issuer, a client id and a secret genuinely
is a dialog's worth of information.

Tabs use the shared `renderSettingsTabBar()` and the same .tabs/.tab markup as
every module settings screen, so this page behaves the way the rest of the
application has already taught people to expect.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [v()](/code/functions/v.md) | 80 | Print a value into an input safely. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 47 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 61 |  | read [tenants](/code/tables/tenants.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/settings_manifest.php](/code/files/includes/settings_manifest.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/toast.js](/code/files/assets/js/toast.js.md), [assets/js/confirm.js](/code/files/assets/js/confirm.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [pageTitle()](/code/functions/pageTitle.md), [renderSettingsTabBar()](/code/functions/renderSettingsTabBar.md), [t()](/code/functions/t.md)

