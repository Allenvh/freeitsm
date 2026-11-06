# api/tickets/get_public_base_url.php

> API endpoint - 78 lines

API Endpoint: the public web address used in links FreeITSM sends out.

Read by the Email Templates settings tab, which needs three different facts and
not one:
  - `base_url`      what an administrator typed, so the field can show it;
  - `is_configured` whether anything was typed AT ALL, which is the only thing
                    that can be trusted from cron — this is what decides whether
                    [ticket_url] gets a warning;
  - `effective_url` what a link would actually come out as right now, which on
                    this request includes the host header and so looks fine even
                    when nothing is configured. Shown as an example, never used
                    to decide whether to warn.

The third one is the trap this endpoint exists to avoid: an admin looking at a
perfectly good sample URL would reasonably conclude everything is set up, when
the same link built at 3am by the mail collector would have no host in it.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_public_base_url.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_EMAIL_TEMPLATES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [publicAbsoluteUrl()](/code/functions/publicAbsoluteUrl.md), [publicBaseUrl()](/code/functions/publicBaseUrl.md), [publicBaseUrlSetting()](/code/functions/publicBaseUrlSetting.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

