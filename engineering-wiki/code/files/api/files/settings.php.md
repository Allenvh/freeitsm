# api/files/settings.php

> API endpoint - 85 lines

Files -> Settings.

GET  ?tab=storage   Cap::FILES_STORAGE  the storage root, size limit, usage
  POST {tab: storage, storage_root, max_upload_mb}
  GET  ?tab=folders   Cap::FILES_FOLDERS  EVERY folder that exists, including
       ones the caller cannot open - names, paths and how many entries each has,
       never their files. This is "admins can see a folder exists" (Ed's
       rule): the way back into an orphaned folder is Take ownership, which is
       permissions.php and is audited.

⚠️ Changing the storage root does NOT move anything. Stored paths are relative
(yyyy/mm/dd/x.bin), so the operator copies the old root's contents to the new
one first; the page says so. Moving gigabytes inside a web request is not
something to attempt.

## The endpoint

| | |
|---|---|
| URL | `api/files/settings.php` |
| Protected by | Permission: Cap::FILES_STORAGE (requireCapabilityJson); Permission: Cap::FILES_FOLDERS (requireCapabilityJson) |
| Reads | `GET tab` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 29 |  | read [files_versions](/code/tables/files_versions.md) |  |
| 65 |  | read [files_permissions](/code/tables/files_permissions.md) |  |
| 66 |  | read [files_items](/code/tables/files_items.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiBody()](/code/functions/filesApiBody.md), [filesApiFail()](/code/functions/filesApiFail.md), [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesAudit()](/code/functions/filesAudit.md), [filesDefaultStorageRoot()](/code/functions/filesDefaultStorageRoot.md), [filesSaveSetting()](/code/functions/filesSaveSetting.md), [filesSetting()](/code/functions/filesSetting.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md)

