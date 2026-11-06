# self-service/login.php

> PHP - 631 lines

Self-Service Portal Login Page

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 69 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 77 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 88 |  | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/self_service.php](/code/files/includes/self_service.php.md), [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), [includes/branding_preview.php](/code/files/includes/branding_preview.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md)

**Calls:** [brandingLoginCss()](/code/functions/brandingLoginCss.md), [brandingLoginDesign()](/code/functions/brandingLoginDesign.md), [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [selfServiceRegistrationEnabled()](/code/functions/selfServiceRegistrationEnabled.md), [ssoPortalRoutingColumnsReady()](/code/functions/ssoPortalRoutingColumnsReady.md), [t()](/code/functions/t.md)

