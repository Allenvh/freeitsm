# system/integrations/provider.php

> PHP - 669 lines

System — one integration provider's connections.

Reached as /system/integrations/jira (see .htaccess) or, without mod_rewrite,
provider.php?provider=jira.

ONE page for every provider rather than a folder each: the form is rendered
from the registry's `credential_fields`, so a provider whose auth looks nothing
like Jira's needs no change here.

⚠️ The connection list is deliberately NOT filtered by company. This is a
CONNECTION-shaped table (tenant_id NULL = shared with every company, set =
pinned to one), the same shape as mailboxes and messaging channels — an admin
configuring routing needs to see them all. What is gated is writing. See the
wiki, Multi-Tenancy-Developer-Guide §1.

⚠️ Secrets never reach this page. The API returns has_credentials as a boolean
and never the token, which is what makes the unfiltered list defensible.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAllTenants()](/code/functions/getAllTenants.md), [integrationsProviderMeta()](/code/functions/integrationsProviderMeta.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md), [integrationsSettingKeys()](/code/functions/integrationsSettingKeys.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

