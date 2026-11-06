# api/messaging/delete_channel.php

> API endpoint - 53 lines

API Endpoint: delete a messaging channel. Past tickets/messages keep their channel_id reference (the row just no longer resolves), so history is intact — only new inbound/outbound on this channel stops.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/delete_channel.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MESSAGING (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | **delete** [messaging_channels](/code/tables/messaging_channels.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [messagingAdminMayAdministerChannel()](/code/functions/messagingAdminMayAdministerChannel.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

