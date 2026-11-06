# api/webchat/get_widgets.php

> API endpoint - 82 lines

API Endpoint: list website chat widgets for the settings page.

Each widget is a messaging_channels row (channel_type='webchat') plus its
webchat_widgets config. The widget_key is public, so it's safe to return; the
embed snippet is built ready to copy-paste.

## The endpoint

| | |
|---|---|
| URL | `api/webchat/get_widgets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_WEBCHAT (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [webchat_widgets](/code/tables/webchat_widgets.md), read [messaging_channels](/code/tables/messaging_channels.md) |  |
| 45 |  | read [sla_calendars](/code/tables/sla_calendars.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [webchatEmbedSnippet()](/code/functions/webchatEmbedSnippet.md)

