# api/tickets/oversize_settings.php

> API endpoint - 68 lines

API Endpoint: what an inbound email over the attachment size limits does - the install DEFAULTS (GH #170). Tickets -> Settings -> Mailboxes.

GET   the defaults, plus the limits themselves (read-only here - they are set
        in System -> Security -> Attachments, and the screen says so)
  POST  {mode, strip_order, alert, alert_to, autoreply, autoreply_text}

A mailbox's own rules are saved with the mailbox (save_mailbox.php,
oversize_policy). See includes/attachment_limits.php for what each one means.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/oversize_settings.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MAILBOXES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [oversizeDefaultReplyText()](/code/functions/oversizeDefaultReplyText.md), [oversizeNormalisePolicy()](/code/functions/oversizeNormalisePolicy.md), [oversizeRules()](/code/functions/oversizeRules.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

