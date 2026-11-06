# api/integrations/save_mapping.php

> API endpoint - 62 lines

Replace the mapping for one connection.

The screen sends the whole set, and the service replaces whole map types at a
time — a mapping the admin removed has to disappear, and diffing rows to work
that out is more code and more ways to be wrong.

Body: { connection_id, maps: { project: {...}, issue_type: {...}, priority: {...} } }

## The endpoint

| | |
|---|---|
| URL | `api/integrations/save_mapping.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md), [integrationsSaveMaps()](/code/functions/integrationsSaveMaps.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md)

