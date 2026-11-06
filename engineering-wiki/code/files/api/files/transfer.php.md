# api/files/transfer.php

> API endpoint - 221 lines

POST {mode: move|copy, target: <folder id, 0 = top level>, entries: [{type: folder|item, id}]} Paste after Cut/Copy, and drag-and-drop between windows.

MOVE  needs Modify on what is moved, and Upload on the target. A folder that
        moves keeps its own entries and, if it inherits, now inherits from its
        new parent - exactly what Windows does, and recorded in the audit row.
  COPY  needs Download on what is copied (a copy is a copy taken away) and
        Upload on the target. Copies inherit at the target and carry no entries
        of their own. A copied file SHARES the source's stored bytes rather than
        writing them twice - a 2 GB copy is instant - so a purge must check
        nothing else points at a .bin (see files_versions in freeitsm.sql).

The top level as a target needs Cap::FILES_FOLDERS, and anything landing there
gives the person doing it Full control, as creating a top-level folder does.
Files cannot live at the top level - only folders.

Each entry succeeds or fails on its own; the response lists what failed and why.

## The endpoint

| | |
|---|---|
| URL | `api/files/transfer.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [filesTransferItem()](/code/functions/filesTransferItem.md) | 63 | RuntimeException = a refusal to report for this one entry. |
| [filesCopyItemRow()](/code/functions/filesCopyItemRow.md) | 98 | A new file row whose single version shares the source's current bytes. |
| [filesTransferFolder()](/code/functions/filesTransferFolder.md) | 126 | Moves or copies one folder to the target folder (0 = the top level). RuntimeException = a refusal to report for this one entry. |
| [filesCopyFolderTree()](/code/functions/filesCopyFolderTree.md) | 184 | Copies a folder and, recursively, every file and subfolder under it that the copier can at least Download, and returns the new folder's id. |
| [filesGrantCreatorFull()](/code/functions/filesGrantCreatorFull.md) | 215 | Gives an analyst Full control on a folder, upgrading an existing entry - used when a folder lands at the top level, as when creating one there. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 79 | `filesTransferItem` | **update** [files_items](/code/tables/files_items.md) (`folder_id`, `name`, `updated_by`, `updated_datetime`) |  |
| 100 | `filesCopyItemRow` | **insert** [files_items](/code/tables/files_items.md) (`folder_id`, `name`, `size_bytes`, `mime_type`, `created_by`, `created_datetime`) |  |
| 105 | `filesCopyItemRow` | **insert** [files_versions](/code/tables/files_versions.md) (`item_id`, `version_no`, `storage_path`, `size_bytes`, `mime_type`, `sha256`, `uploaded_by`, `uploaded_datetime`) |  |
| 108 | `filesCopyItemRow` | **update** [files_items](/code/tables/files_items.md) (`current_version_id`) |  |
| 147 | `filesTransferFolder` | **update** [files_folders](/code/tables/files_folders.md) (`parent_id`, `name`, `updated_by`, `updated_datetime`) |  |
| 186 | `filesCopyFolderTree` | **insert** [files_folders](/code/tables/files_folders.md) (`parent_id`, `name`, `inherit_permissions`, `created_by`, `created_datetime`) |  |
| 194 | `filesCopyFolderTree` | read [files_items](/code/tables/files_items.md) |  |
| 217 | `filesGrantCreatorFull` | **insert** [files_permissions](/code/tables/files_permissions.md) (`folder_id`, `principal_type`, `principal_id`, `level`, `granted_by`, `granted_datetime`) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiBody()](/code/functions/filesApiBody.md), [filesApiFail()](/code/functions/filesApiFail.md), [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesAudit()](/code/functions/filesAudit.md), [filesFreeName()](/code/functions/filesFreeName.md), [filesHasCap()](/code/functions/filesHasCap.md), [filesItem()](/code/functions/filesItem.md), [filesNeed()](/code/functions/filesNeed.md)

