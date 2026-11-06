# self-service/my-equipment.php

> PHP - 148 lines

Self-Service Portal — My equipment.

The kit assigned to the signed-in person, so they can read a serial number off
the screen instead of raising a ticket to ask what it is.

⚠️ NOTHING HERE DECIDES WHAT SOMEBODY MAY SEE. It draws what
api/self-service/get_my_assets.php returns, and that endpoint takes the user
from the session, has no id parameter and offers no search — a portal user can
only ever reach equipment assigned to them. That endpoint predates this page
(it feeds the asset picker on a new ticket); this is a second, read-only view
of the same answer, so the two cannot disagree about who has what.

🔑 Reached only when an administrator has turned it on (System → Self-service
portal). Off by default, so an install that never asked for it does not get a
new tab. The nav hides the link and this page re-checks — a hidden link is not
a permission.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/self_service_settings.php](/code/files/includes/self_service_settings.php.md), [self-service/includes/header.php](/code/files/self-service/includes/header.php.md), [self-service/includes/footer.php](/code/files/self-service/includes/footer.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [selfServicePortalSettings()](/code/functions/selfServicePortalSettings.md), [t()](/code/functions/t.md)

