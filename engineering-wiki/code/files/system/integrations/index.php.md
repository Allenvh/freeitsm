# system/integrations/index.php

> PHP - 173 lines

System — Integrations landing.

The provider grid. One card today (Jira); GitHub, GitLab and Azure DevOps land
here as soon as their connectors exist, with no change to this page — the grid
is rendered from `integrationsAvailableProviders()`.

Deliberately NOT showing "coming soon" cards for unbuilt providers. A page that
grows beats a page of promises, especially in a repo people read before they
install.

Each card links to /system/integrations/<key>, which .htaccess rewrites to
provider.php.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | read [integration_connections](/code/tables/integration_connections.md) |  |
| 58 |  | read [messaging_channels](/code/tables/messaging_channels.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [includes/search/tika.php](/code/files/includes/search/tika.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [integrationsAvailableProviders()](/code/functions/integrationsAvailableProviders.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md), [tikaConfigured()](/code/functions/tikaConfigured.md)

