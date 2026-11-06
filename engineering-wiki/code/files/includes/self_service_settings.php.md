# includes/self_service_settings.php

> PHP - 130 lines

Self-service portal settings — read from one place, by both sides.

🔑 The settings screen and the portal itself both need these, and a second
copy of the defaults is how the two drift: the screen shows a switch as off
while the portal behaves as though it were on. One function, both callers.

Every value degrades to the behaviour the portal had before these settings
existed, so an install that has never opened the screen is unchanged.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [selfServicePortalSettings()](/code/functions/selfServicePortalSettings.md) | 37 | Every self-service portal setting, with its default. |
| [selfServicePortalLogoUrl()](/code/functions/selfServicePortalLogoUrl.md) | 108 | The portal logo as a URL the browser can actually fetch, or '' when there is none (the caller then falls back to the main branding logo). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `selfServicePortalSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Included by:** [api/self-service/close_ticket.php](/code/files/api/self-service/close_ticket.php.md), [api/system/self_service_portal.php](/code/files/api/system/self_service_portal.php.md), [self-service/includes/header.php](/code/files/self-service/includes/header.php.md), [self-service/my-equipment.php](/code/files/self-service/my-equipment.php.md), [system/self-service/index.php](/code/files/system/self-service/index.php.md)

