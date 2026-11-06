# api/service-status/save_uptime_settings.php

> API endpoint - 54 lines

API: save the uptime reporting settings (discussion #59).

Two settings only, and both are genuinely nobody else's property:
  status_uptime_window_days   the default measurement window
  status_uptime_show_portal   whether customers see the figures

⚠️ What COUNTS as downtime is NOT here. That is a property of each impact
level (service_impact_levels.counts_as_downtime) and is edited on the Impact
levels tab, so a custom level added later is asked the question when it is
created rather than needing a second list to be remembered.

## The endpoint

| | |
|---|---|
| URL | `api/service-status/save_uptime_settings.php` |
| Protected by | Module access: service-status (requireModuleAccessJson); Permission: Cap::SERVICE_STATUS_UPTIME (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/service_uptime.php](/code/files/includes/services/service_uptime.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

