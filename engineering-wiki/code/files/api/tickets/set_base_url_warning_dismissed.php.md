# api/tickets/set_base_url_warning_dismissed.php

> API endpoint - 63 lines

API Endpoint: acknowledge the "no public web address is set" warning.

Stored as system_settings 'public_base_url_warning_dismissed'.

WHY THIS IS DISMISSIBLE AT ALL. The warning fires when a template uses
[ticket_url] and nothing is configured, which is very often a real fault — but
not always. An install whose templates are only ever sent by an analyst pressing
something, on a single host with no reverse proxy, gets a working link from the
request every time. Telling that administrator they have a problem on every visit
for the rest of the install's life is how a warning stops being read, taking the
ones that matter with it. Same rule as the mailbox health marks: warnings can be
acknowledged, errors never (see includes/mailbox_health.php).

Dismissing is per install rather than per person, because the thing being
acknowledged is a fact about the install, not a preference. Setting the address
later resolves it for everyone regardless of what was dismissed.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/set_base_url_warning_dismissed.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_EMAIL_TEMPLATES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

