# system/integrations/help.php

> PHP - 476 lines

System — how to set up an issue tracker, end to end.

Reached as /system/integrations/jira/help (see .htaccess) or, without
mod_rewrite, help.php?provider=jira.

⚠️ Written for somebody who does NOT already know Jira. That is the whole
point of it existing: an admin who knows Jira can read the mapping screen and
guess, and everybody else is looking at a modal full of dropdowns with no idea
what a "project key" is. Prose over reference tables, and every step says what
happens if you skip it.

ONE page for every provider, like provider.php — the provider's name comes
from the registry. The Jira-specific parts (where Atlassian keeps API tokens,
what a project key looks like) are guarded on the provider key so a second
connector can add its own without unpicking this.

Layout deliberately mirrors tickets/help.php (left nav, hero, scroll-spy) so
every help screen in the product feels like the same screen.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md), [system/integrations/help_slack.php](/code/files/system/integrations/help_slack.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [integrationsProviderMeta()](/code/functions/integrationsProviderMeta.md), [pageTitle()](/code/functions/pageTitle.md), [t()](/code/functions/t.md)

