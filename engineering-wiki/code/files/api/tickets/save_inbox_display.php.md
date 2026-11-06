# api/tickets/save_inbox_display.php

> API endpoint - 97 lines

API Endpoint: save what the inbox row shows.

Two different things behind one endpoint, with two different permissions:

  scope = "me"      this analyst's own preference. No capability — it is a view
                    preference, and gating it would mean asking an administrator
                    for permission to find your own queue readable.
  scope = "install" the default for everyone who has not chosen. That IS
                    administration, so it needs Cap::TICKETS_MANAGE.
  scope = "reset"   forget my choice and follow the install default again.

See includes/inbox_display.php for the registry these values are validated
against, and for why priority defaults to the left edge rather than a dot.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_inbox_display.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MANAGE (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | **delete** [user_preferences](/code/tables/user_preferences.md) |  |
| 64 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 81 |  | **insert** [user_preferences](/code/tables/user_preferences.md) (`analyst_id`, `preference_key`, `preference_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/inbox_display.php](/code/files/includes/inbox_display.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [inboxDisplayInstallDefault()](/code/functions/inboxDisplayInstallDefault.md), [inboxDisplayIsPersonal()](/code/functions/inboxDisplayIsPersonal.md), [inboxDisplayNormalise()](/code/functions/inboxDisplayNormalise.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

