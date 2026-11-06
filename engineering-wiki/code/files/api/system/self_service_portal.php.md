# api/system/self_service_portal.php

> API endpoint - 168 lines

API: how the self-service portal looks and what it lets people do.

GET   the current settings
POST  save them

🔑 WHY THIS IS NOT PART OF api/system/branding.php.

Branding owns the ONE logo that the app, the login screen and the emails all
share. These settings are the portal's own: a separate logo for customers, a
header colour, and two switches that change what the portal lets somebody do.
Folding them into branding would put "may a customer close their own ticket?"
on a screen about logos.

🔴 Gated on System administrators like every other System endpoint. Two of
these decide what CUSTOMERS may do, and one of them closes tickets.

## The endpoint

| | |
|---|---|
| URL | `api/system/self_service_portal.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `POST remove_logo` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 58 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 151 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/self_service_settings.php](/code/files/includes/self_service_settings.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [selfServicePortalSettings()](/code/functions/selfServicePortalSettings.md), [uploadPrepareWebServableDir()](/code/functions/uploadPrepareWebServableDir.md), [uploadStoreFile()](/code/functions/uploadStoreFile.md)

