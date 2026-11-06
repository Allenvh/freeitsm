# api/integrations/delete_connection.php

> API endpoint - 59 lines

Delete an integration connection.

⚠️ Refuses while links still point at it. The alternative — cascading the
delete — would silently erase the record that ticket SD-1042 was raised in Jira
as OPS-412, and that reference has already been seen by people. Same principle
as merge, where a merged ticket is never deleted because its reference appears
in notifications somebody has read.

The admin's route out is to switch the connection off, which keeps every link
readable but stops it being used. That is offered in the error rather than left
to be guessed at.

## The endpoint

| | |
|---|---|
| URL | `api/integrations/delete_connection.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [integration_links](/code/tables/integration_links.md) |  |
| 52 |  | **delete** [integration_connections](/code/tables/integration_connections.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md)

