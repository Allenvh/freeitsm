# api/messaging/save_channel.php

> API endpoint - 247 lines

API Endpoint: create/update a messaging channel (WhatsApp etc.).

Credentials are provider-specific and stored as an encrypted JSON blob:
  twilio: { account_sid, auth_token }
  meta:   { phone_number_id, access_token, app_secret }
On update, a blank or masked (***) secret field means "keep the existing value".

## The endpoint

| | |
|---|---|
| URL | `api/messaging/save_channel.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MESSAGING (requireCapabilityJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [provided()](/code/functions/provided.md) | 43 | Treat blank or all-asterisk values as "unchanged" so masked secrets aren't wiped. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 117 |  | read [tenants](/code/tables/tenants.md) |  |
| 221 |  | **update** [messaging_channels](/code/tables/messaging_channels.md) (`name`, `channel_type`, `provider`, `phone_number`, `credentials`, `verify_token`, `ingress_mode`, `relay_secret`, `tenant_id`, `is_active`) |  |
| 233 |  | **insert** [messaging_channels](/code/tables/messaging_channels.md) (`name`, `channel_type`, `provider`, `phone_number`, `credentials`, `verify_token`, `ingress_mode`, `relay_secret`, `tenant_id`, `is_active`, `channel_ref`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [analystCanAssignTenant()](/code/functions/analystCanAssignTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [encryptValue()](/code/functions/encryptValue.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md)

## Traps

> [!WARNING]
> **Line 215:** only write channel_ref for a provider that owns it here. Other providers keep their own value in it (Slack's workspace id), and a save that blanked it for them would quietly break a working channel.

