# api/system/save_branding.php

> API endpoint - 258 lines

API: Save organisation-wide branding settings (text slots + optional logo upload).

Accepts multipart/form-data so the logo file can ride along in the same
request as the text slots. Logo file is optional — pass `remove_logo=1` to
explicitly clear an existing logo, otherwise omit the file to leave the
existing logo unchanged.

Text slots are limited to 200 chars each — these are toolbar-strip text,
not paragraphs. Tokens (`{{title}}` etc.) are passed through verbatim and
resolved at render time client-side.

POST fields:
  header_left, header_center, header_right
  footer_left, footer_center, footer_right
  remove_logo (optional, '1' to clear the stored logo)
  logo (optional file upload)

## The endpoint

| | |
|---|---|
| URL | `api/system/save_branding.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `POST landing_page`, `POST files_logo_mode`, `POST files_logo_position`, `POST system_name`, `POST back_link_label`, `POST back_link_url`, `POST back_link_where`, `POST remove_logo` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 181 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 243 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/branding.php](/code/files/includes/branding.php.md), [includes/landing.php](/code/files/includes/landing.php.md), [includes/back_link.php](/code/files/includes/back_link.php.md)

**Calls:** [backLinkUrlValid()](/code/functions/backLinkUrlValid.md), [brandingLoginFields()](/code/functions/brandingLoginFields.md), [brandingLoginKey()](/code/functions/brandingLoginKey.md), [brandingLoginValidate()](/code/functions/brandingLoginValidate.md), [brandingPathIsSafe()](/code/functions/brandingPathIsSafe.md), [brandingScopes()](/code/functions/brandingScopes.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [landingIsValid()](/code/functions/landingIsValid.md), [landingTargets()](/code/functions/landingTargets.md), [uploadPrepareWebServableDir()](/code/functions/uploadPrepareWebServableDir.md), [uploadStoreFile()](/code/functions/uploadStoreFile.md)

