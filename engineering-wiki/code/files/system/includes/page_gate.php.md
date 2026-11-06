# system/includes/page_gate.php

> PHP - 31 lines

System module page gate. Require it on every System page AFTER `$current_page` is set and BEFORE anything is output.

header.php makes the same two checks, but it is included in the middle of the
<body>, after the <head> has already been sent. By then header('Location')
can only raise "headers already sent" and the exit that follows leaves a
half-drawn page: a signed-out analyst saw a blank System page instead of the
login screen, and a non-admin saw one instead of being sent home. The checks
in header.php stay as a backstop; this is the one that can still redirect.

Cosmetic like header.php's - the System APIs enforce admin themselves with
`requireAdminJson()`.

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md)

**Included by:** [system/ai/index.php](/code/files/system/ai/index.php.md), [system/analysts/index.php](/code/files/system/analysts/index.php.md), [system/api/docs.php](/code/files/system/api/docs.php.md), [system/api/index.php](/code/files/system/api/index.php.md), [system/branding/index.php](/code/files/system/branding/index.php.md), [system/calendar-sync/index.php](/code/files/system/calendar-sync/index.php.md), [system/colours/index.php](/code/files/system/colours/index.php.md), [system/companies/index.php](/code/files/system/companies/index.php.md), [system/contributors/index.php](/code/files/system/contributors/index.php.md), [system/cost-centres/index.php](/code/files/system/cost-centres/index.php.md), [system/date-formats/index.php](/code/files/system/date-formats/index.php.md), [system/db-verify/index.php](/code/files/system/db-verify/index.php.md), [system/debug-tools/includes/tool-page.php](/code/files/system/debug-tools/includes/tool-page.php.md), [system/debug-tools/index.php](/code/files/system/debug-tools/index.php.md), [system/demo-data/index.php](/code/files/system/demo-data/index.php.md), [system/docker/index.php](/code/files/system/docker/index.php.md), [system/email-routing-test/index.php](/code/files/system/email-routing-test/index.php.md), [system/encryption/index.php](/code/files/system/encryption/index.php.md), [system/feature-bingo/index.php](/code/files/system/feature-bingo/index.php.md), [system/help/index.php](/code/files/system/help/index.php.md), [system/help/_init.php](/code/files/system/help/_init.php.md), [system/index.php](/code/files/system/index.php.md), [system/integrations/help.php](/code/files/system/integrations/help.php.md), [system/integrations/index.php](/code/files/system/integrations/index.php.md), [system/integrations/provider.php](/code/files/system/integrations/provider.php.md), [system/integrations/slack.php](/code/files/system/integrations/slack.php.md), [system/integrations/tika.php](/code/files/system/integrations/tika.php.md), [system/managers/index.php](/code/files/system/managers/index.php.md), [system/modules/index.php](/code/files/system/modules/index.php.md), [system/orphaned-tickets/index.php](/code/files/system/orphaned-tickets/index.php.md), [system/photo-album/index.php](/code/files/system/photo-album/index.php.md), [system/portal-profile/index.php](/code/files/system/portal-profile/index.php.md), [system/preferences/index.php](/code/files/system/preferences/index.php.md), [system/roles/index.php](/code/files/system/roles/index.php.md), [system/search/index.php](/code/files/system/search/index.php.md), [system/security/index.php](/code/files/system/security/index.php.md), [system/self-service/index.php](/code/files/system/self-service/index.php.md), [system/sso/carddav.php](/code/files/system/sso/carddav.php.md), [system/sso/index.php](/code/files/system/sso/index.php.md), [system/sso/provider.php](/code/files/system/sso/provider.php.md), [system/status-portal/index.php](/code/files/system/status-portal/index.php.md), [system/teams/index.php](/code/files/system/teams/index.php.md), [system/topology/index.php](/code/files/system/topology/index.php.md), [system/webhooks/index.php](/code/files/system/webhooks/index.php.md)

**Calls:** [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md)

