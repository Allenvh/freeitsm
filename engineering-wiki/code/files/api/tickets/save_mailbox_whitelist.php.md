# api/tickets/save_mailbox_whitelist.php

> API endpoint - 67 lines

API Endpoint: Save whitelist entries for a mailbox POST: { mailbox_id, entries: [{ entry_type, entry_value }] } Replaces all existing entries for the mailbox.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_mailbox_whitelist.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MAILBOXES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | **delete** [mailbox_email_whitelist](/code/tables/mailbox_email_whitelist.md) |  |
| 43 |  | **insert** [mailbox_email_whitelist](/code/tables/mailbox_email_whitelist.md) (`mailbox_id`, `entry_type`, `entry_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

