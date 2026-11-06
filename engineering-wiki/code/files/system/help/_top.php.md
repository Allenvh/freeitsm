# system/help/_top.php

> PHP - 69 lines

Shared chrome for a System help topic page (head + CSS + header + hero + sidebar, then opens the content area). Expects `$helpSlug` — the page's key in _registry.php — from which the hero, standfirst and sidebar nav are resolved. A page can still set `$helpHero` / `$helpSub` / `$helpNav` itself to override. Must be included at top-level scope (see _init.php). Pair with _bottom.php.

## How it hangs together

**Includes:** [system/includes/header.php](/code/files/system/includes/header.php.md)

**Included by:** [system/help/ai.php](/code/files/system/help/ai.php.md), [system/help/analysts.php](/code/files/system/help/analysts.php.md), [system/help/api.php](/code/files/system/help/api.php.md), [system/help/branding.php](/code/files/system/help/branding.php.md), [system/help/calendar-sync.php](/code/files/system/help/calendar-sync.php.md), [system/help/colours.php](/code/files/system/help/colours.php.md), [system/help/companies.php](/code/files/system/help/companies.php.md), [system/help/contributors.php](/code/files/system/help/contributors.php.md), [system/help/cost-centres.php](/code/files/system/help/cost-centres.php.md), [system/help/date-formats.php](/code/files/system/help/date-formats.php.md), [system/help/db-verify.php](/code/files/system/help/db-verify.php.md), [system/help/debug-tools.php](/code/files/system/help/debug-tools.php.md), [system/help/demo-data.php](/code/files/system/help/demo-data.php.md), [system/help/docker.php](/code/files/system/help/docker.php.md), [system/help/email-routing-test.php](/code/files/system/help/email-routing-test.php.md), [system/help/encryption.php](/code/files/system/help/encryption.php.md), [system/help/feature-bingo.php](/code/files/system/help/feature-bingo.php.md), [system/help/integrations.php](/code/files/system/help/integrations.php.md), [system/help/managers.php](/code/files/system/help/managers.php.md), [system/help/modules.php](/code/files/system/help/modules.php.md), [system/help/orphaned-tickets.php](/code/files/system/help/orphaned-tickets.php.md), [system/help/photo-album.php](/code/files/system/help/photo-album.php.md), [system/help/portal-profile.php](/code/files/system/help/portal-profile.php.md), [system/help/preferences.php](/code/files/system/help/preferences.php.md), [system/help/roles.php](/code/files/system/help/roles.php.md), [system/help/search.php](/code/files/system/help/search.php.md), [system/help/security.php](/code/files/system/help/security.php.md), [system/help/sso-groups.php](/code/files/system/help/sso-groups.php.md), [system/help/sso.php](/code/files/system/help/sso.php.md), [system/help/status-portal.php](/code/files/system/help/status-portal.php.md), [system/help/teams.php](/code/files/system/help/teams.php.md), [system/help/topology.php](/code/files/system/help/topology.php.md), [system/help/webhooks.php](/code/files/system/help/webhooks.php.md)

**Loads scripts:** [assets/js/tz.js](/code/files/assets/js/tz.js.md)

**Calls:** [getHelpTopic()](/code/functions/getHelpTopic.md), [pageTitle()](/code/functions/pageTitle.md)

