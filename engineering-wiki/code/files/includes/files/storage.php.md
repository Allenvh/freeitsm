# includes/files/storage.php

> PHP - 212 lines

Files — where the bytes live, and the module's settings.

Every file is stored as <storage root>/yyyy/mm/dd/<32 hex>.bin, dated by the
day it was uploaded. Nothing user-controlled ever reaches the filesystem: the
original name, type, size and SHA-256 live in files_versions only. A .bin
cannot be executed by any web server, and cannot be fetched at all - the root
is denied outright (uploadPrepareDir) and every read goes through
api/files/download.php, which checks the folder's permissions first.

The root defaults to uploads/files/ inside the app (nginx's /uploads/ block
and the generated .htaccess/web.config deny it). Files -> Settings -> Storage
can move it anywhere the web server can write - ideally outside the web root.
On Docker it is the `files` volume.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [filesSetting()](/code/functions/filesSetting.md) | 30 | Reads a Files setting from system_settings, cached per request; blank or missing gives `$default`. |
| [filesSaveSetting()](/code/functions/filesSaveSetting.md) | 48 | Saves one Files setting to system_settings (insert or update). |
| [filesDefaultStorageRoot()](/code/functions/filesDefaultStorageRoot.md) | 55 | The storage folder used when none is set: uploads/files under the application root. |
| [filesStorageRoot()](/code/functions/filesStorageRoot.md) | 61 | The storage root, created and guarded on first use. No trailing slash. |
| [filesMaxUploadBytes()](/code/functions/filesMaxUploadBytes.md) | 69 | The largest file an analyst may upload, in bytes, from the files_max_upload_mb setting. |
| [filesNewStoragePath()](/code/functions/filesNewStoragePath.md) | 77 | A fresh relative path for today: 2026/10/10/<hex>.bin (directories created). |
| [filesAbsolutePath()](/code/functions/filesAbsolutePath.md) | 94 | The absolute path of a stored version, or null if the row's path is not one we could have written. A row reading "../../config.php" must never be served. |
| [filesIncomingPath()](/code/functions/filesIncomingPath.md) | 105 | Where pieces of an in-progress upload collect. |
| [filesCleanName()](/code/functions/filesCleanName.md) | 120 | A file or folder name as a person typed it, made safe to store and display. |
| [filesDetectMime()](/code/functions/filesDetectMime.md) | 131 | The mime type we record for a stored file: sniffed, else guessed from the name. |
| [filesGetPrefs()](/code/functions/filesGetPrefs.md) | 140 | Per-person desktop preferences, in user_preferences with a files_ prefix. |
| [filesSavePref()](/code/functions/filesSavePref.md) | 162 | Saves one of the analyst's own Files preferences (insert or update). |
| [filesDesktopLogo()](/code/functions/filesDesktopLogo.md) | 176 | The logo for the desktop corner, per System -> Branding: files_logo_mode 'company' (default) \| 'none' files_logo_position 'top-right' (default) \| 'bottom-right' 'company' = the analyst's active company's logo, else the organisation logo. |
| [filesCompanyLogoPathIsSafe()](/code/functions/filesCompanyLogoPathIsSafe.md) | 208 | A company logo lives in system/uploads/branding/companies/ under our own random name. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 | `filesSetting` | read [system_settings](/code/tables/system_settings.md) |  |
| 50 | `filesSaveSetting` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 142 | `filesGetPrefs` | read [user_preferences](/code/tables/user_preferences.md) |  |
| 164 | `filesSavePref` | **insert** [user_preferences](/code/tables/user_preferences.md) (`analyst_id`, `preference_key`, `preference_value`, `updated_datetime`) |  |
| 187 | `filesDesktopLogo` | read [tenants](/code/tables/tenants.md) |  |

## How it hangs together

**Includes:** [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/branding.php](/code/files/includes/branding.php.md)

**Included by:** [files/index.php](/code/files/files/index.php.md), [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [attachmentServeRules()](/code/functions/attachmentServeRules.md), [brandingLogoUrl()](/code/functions/brandingLogoUrl.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [uploadCleanName()](/code/functions/uploadCleanName.md), [uploadDetectMime()](/code/functions/uploadDetectMime.md), [uploadPrepareDir()](/code/functions/uploadPrepareDir.md)

