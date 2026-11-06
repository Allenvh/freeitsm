# api/integrations/save_connection.php

> API endpoint - 142 lines

Create or update an integration connection.

⚠️ Credentials are ENCRYPTED AT REST (encryptValue → the ENC: prefix), the same
as messaging_channels.credentials.

⚠️ An empty credentials object on an EDIT means "keep what is stored", not
"clear it". The settings page never receives the stored secret (see
list_connections.php), so it cannot send it back — without this rule, every
edit of a connection's name would silently wipe its token. The page says so in
the form; this is the half that enforces it.

## The endpoint

| | |
|---|---|
| URL | `api/integrations/save_connection.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 118 |  | **update** [integration_connections](/code/tables/integration_connections.md) (`name`, `base_url`, `credentials`, `tenant_id`, `is_active`, `inbound_enabled`, `send_attachments`, `account_identity`) |  |
| 128 |  | **insert** [integration_connections](/code/tables/integration_connections.md) (`name`, `provider`, `base_url`, `auth_type`, `credentials`, `tenant_id`, `is_active`, `inbound_enabled`, `send_attachments`, `created_by`, `account_identity`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Calls:** [analystCanAssignTenant()](/code/functions/analystCanAssignTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [encryptValue()](/code/functions/encryptValue.md), [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md), [integrationsProviderMeta()](/code/functions/integrationsProviderMeta.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md)

