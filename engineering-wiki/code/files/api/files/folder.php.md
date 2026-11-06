# api/files/folder.php

> API endpoint - 131 lines

POST {action, ...} - change a folder.

create   {parent_id (0 = top level), name}   Upload on the parent; at the top
           level, Cap::FILES_FOLDERS - and the creator is granted Full control,
           because a new top-level folder grants nothing and they would
           otherwise lock themselves out of what they just made.
  rename   {id, name}                          Modify
  delete   {id}                                Modify. Soft: the folder and
           everything under it go to the recycle bin (deleted_datetime).
  watermark {id, value: null|0|1}             Full control. Whether the viewer
           stamps the viewer's name, time and IP across files from here.
           null = as the parent folder (the default).
  inherit  {id, inherit: bool, copy: bool}     Full control. Turning it OFF
           asks, like Windows, whether to copy the inherited entries in as the
           folder's own (copy=true) or start from just its own (copy=false).

## The endpoint

| | |
|---|---|
| URL | `api/files/folder.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | **insert** [files_folders](/code/tables/files_folders.md) (`parent_id`, `name`, `inherit_permissions`, `created_by`, `created_datetime`) |  |
| 42 |  | **insert** [files_permissions](/code/tables/files_permissions.md) (`folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime`) |  |
| 64 |  | **update** [files_folders](/code/tables/files_folders.md) (`name`, `updated_by`, `updated_datetime`) |  |
| 74 |  | **update** [files_folders](/code/tables/files_folders.md) (`deleted_by`, `deleted_datetime`) |  |
| 86 |  | **update** [files_folders](/code/tables/files_folders.md) (`watermark`, `updated_by`, `updated_datetime`) |  |
| 111 |  | **insert** [files_permissions](/code/tables/files_permissions.md) (`folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime`) |  |
| 120 |  | **update** [files_folders](/code/tables/files_folders.md) (`inherit_permissions`, `updated_by`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiBody()](/code/functions/filesApiBody.md), [filesApiFail()](/code/functions/filesApiFail.md), [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesAudit()](/code/functions/filesAudit.md), [filesCleanName()](/code/functions/filesCleanName.md), [filesFreeName()](/code/functions/filesFreeName.md), [filesHasCap()](/code/functions/filesHasCap.md), [filesNameTaken()](/code/functions/filesNameTaken.md), [filesNeed()](/code/functions/filesNeed.md)

