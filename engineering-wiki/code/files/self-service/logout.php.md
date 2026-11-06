# self-service/logout.php

> PHP - 50 lines

Self-Service Portal Logout Clears self-service session vars only (preserves any analyst session). If the requester signed in via SSO, also ends the session at the identity provider (single logout), then returns to the portal login page.

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/oidc.php](/code/files/includes/oidc.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [oidcDiscover()](/code/functions/oidcDiscover.md), [oidcGetProvider()](/code/functions/oidcGetProvider.md), [requestScheme()](/code/functions/requestScheme.md)

