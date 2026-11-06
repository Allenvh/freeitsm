# api/integrations/test_connection.php

> API endpoint - 102 lines

Test an integration connection's credentials with a read-only call.

Works on an UNSAVED form as well as a stored connection, so an admin can prove
the token before committing it. When an id is supplied, whatever the form left
blank falls back to what is stored — the page never receives the stored secret,
so without that merge, testing an existing connection would always fail.

⚠️ A successful test is also where account_identity is captured — who our token
authenticates as. Nothing reads it until comment sync, but it is half of echo
suppression (an inbound event authored by this identity is our own write coming
back), and back-filling it for links that already exist is miserable. The same
call settles the Cloud/Data Center flavour, so both are persisted here.

## The endpoint

| | |
|---|---|
| URL | `api/integrations/test_connection.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 76 |  | **update** [integration_connections](/code/tables/integration_connections.md) (`account_identity`, `credentials`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/ssl.php](/code/files/includes/ssl.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [encryptValue()](/code/functions/encryptValue.md), [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md), [integrationsProviderFor()](/code/functions/integrationsProviderFor.md), [integrationsProviderMeta()](/code/functions/integrationsProviderMeta.md)

