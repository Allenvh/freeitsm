# api/system/service_status_portal.php

> API endpoint - 70 lines

API: what the self-service portal shows about service status (#99).

GET                              the current settings
POST { updates, mode, days }     save them

🔴 This switch decides what END USERS can read. Turning it on publishes
incident titles and every update marked external, so it is gated on the
System module rather than on Service Status — the people who administer the
install, not everyone who can raise an incident.

## The endpoint

| | |
|---|---|
| URL | `api/system/service_status_portal.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ssPortalSettings()](/code/functions/ssPortalSettings.md)

