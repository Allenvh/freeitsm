# api/webchat/save_widget.php

> API endpoint - 190 lines

API Endpoint: create/update a website chat widget.

A widget is stored as two rows kept in step inside one transaction:
  messaging_channels  — the channel spine (name, company routing, active flag).
                        channel_type='webchat', provider='freeitsm' (no external
                        provider, no credentials).
  webchat_widgets     — the browser-facing config (public key, origins, greeting,
                        colour, launcher text, offline message, email gate).

The widget_key is generated once on create and never changes (it's baked into the
customer's embed snippet). There are no secrets here, so nothing is encrypted.

## The endpoint

| | |
|---|---|
| URL | `api/webchat/save_widget.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_WEBCHAT (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 76 |  | read [sla_calendars](/code/tables/sla_calendars.md) |  |
| 91 |  | read [tenants](/code/tables/tenants.md) |  |
| 109 |  | read [webchat_widgets](/code/tables/webchat_widgets.md) |  |
| 124 |  | **update** [messaging_channels](/code/tables/messaging_channels.md) (`name`, `tenant_id`, `is_active`) |  |
| 130 |  | **update** [webchat_widgets](/code/tables/webchat_widgets.md) (`allowed_origins`, `greeting`, `accent_colour`, `launcher_text`, `offline_message`, `require_email`, `business_calendar_id`, `email_when_away`, `ai_enabled`, `ai_mode`, `ai_offer_agent`, `ai_offer_email`) |  |
| 148 |  | **insert** [messaging_channels](/code/tables/messaging_channels.md) (`name`, `channel_type`, `provider`, `tenant_id`, `is_active`) |  |
| 157 |  | read [webchat_widgets](/code/tables/webchat_widgets.md) |  |
| 162 |  | **insert** [webchat_widgets](/code/tables/webchat_widgets.md) (`channel_id`, `widget_key`, `allowed_origins`, `greeting`, `accent_colour`, `launcher_text`, `offline_message`, `require_email`, `business_calendar_id`, `email_when_away`, `ai_enabled`, `ai_mode`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [analystCanAssignTenant()](/code/functions/analystCanAssignTenant.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [webchatEmbedSnippet()](/code/functions/webchatEmbedSnippet.md), [webchatGenerateKey()](/code/functions/webchatGenerateKey.md), [webchatParseOrigins()](/code/functions/webchatParseOrigins.md)

