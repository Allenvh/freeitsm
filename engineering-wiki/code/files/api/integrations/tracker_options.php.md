# api/integrations/tracker_options.php

> API endpoint - 80 lines

What does this tracker offer? Projects, issue types and priorities, live from the provider — the lists the mapping screen turns into dropdowns.

Until now nothing called `listProjects()`/listIssueTypes() over HTTP at all:
they existed on the connector and were exercised only by tests, which is why
the workflow action still asks an admin to type a project key by hand.

⚠️ Every call here reaches out to somebody else's API, so it is deliberately
on demand (the screen asks when it needs a list) rather than on page load.

GET ?connection_id=8&what=projects
    ?connection_id=8&what=issue_types&project=KAN
    ?connection_id=8&what=priorities

## The endpoint

| | |
|---|---|
| URL | `api/integrations/tracker_options.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET connection_id`, `GET what`, `GET project` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md), [integrationsProviderFor()](/code/functions/integrationsProviderFor.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md)

