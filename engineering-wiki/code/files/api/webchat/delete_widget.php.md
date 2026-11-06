# api/webchat/delete_widget.php

> API endpoint - 58 lines

API Endpoint: delete a website chat widget.

Deletes the underlying messaging_channels row; the webchat_widgets config row is
removed with it via the ON DELETE CASCADE FK. Past tickets/messages keep their
channel_id reference (the row just no longer resolves), so history is intact —
only new conversations on this widget stop.

## The endpoint

| | |
|---|---|
| URL | `api/webchat/delete_widget.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_WEBCHAT (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [webchat_widgets](/code/tables/webchat_widgets.md) |  |
| 50 |  | **delete** [messaging_channels](/code/tables/messaging_channels.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

