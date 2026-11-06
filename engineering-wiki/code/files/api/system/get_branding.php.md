# api/system/get_branding.php

> API endpoint - 112 lines

API: Get organisation-wide branding settings.

Returns the company logo URL (if uploaded) and the default header/footer
template slots (left/centre/right) that get applied to new diagrams and
acted as the fallback for any per-diagram slot that's NULL.

Used by:
  - system/branding/index.php to populate the settings form
  - Network Mapper diagram editor to render header/footer overlays
  - (future) PDF/PNG exporters in any module that wants branded output

## The endpoint

| | |
|---|---|
| URL | `api/system/get_branding.php` |
| Protected by | Signed-in analyst (session check) (via includes/csrf.php) |
| Called from | [assets/js/network-mapper.js](/code/files/assets/js/network-mapper.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 95 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 101 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/landing.php](/code/files/includes/landing.php.md), [includes/back_link.php](/code/files/includes/back_link.php.md)

**Calls:** [backLinkSettings()](/code/functions/backLinkSettings.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [landingInstallDefault()](/code/functions/landingInstallDefault.md)

