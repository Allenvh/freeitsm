# api/files/upload.php

> API endpoint - 163 lines

Chunked uploads - how a 2 GB .exe gets in past PHP's upload limits and any proxy's body limit: the browser sends it in FILES_CHUNK_BYTES pieces.

POST ?action=start   {folder_id, name, size, on_conflict?: ask|version|rename}
       -> {token, chunk}                       or {conflict: true, item_id, name}
  POST ?action=chunk&token=&offset=   raw bytes as the body (NOT multipart -
       upload_max_filesize does not apply; each piece is far under post_max_size)
  POST ?action=finish  {token}       -> {item_id, version_no, name}
  POST ?action=cancel  {token}

Upload permission on the folder is checked at start AND finish: a grant removed
while a big file is still going up must stop it landing.

The pieces collect in <root>/_incoming/<token>.part. On finish the file is
hashed (SHA-256) and moved to <root>/yyyy/mm/dd/<random>.bin. The original name
never touches the filesystem.

## The endpoint

| | |
|---|---|
| URL | `api/files/upload.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |
| Reads | `GET action`, `GET token`, `GET offset` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 |  | read [files_items](/code/tables/files_items.md) |  |
| 59 |  | read [files_uploads](/code/tables/files_uploads.md) |  |
| 62 |  | **delete** [files_uploads](/code/tables/files_uploads.md) |  |
| 71 |  | **insert** [files_uploads](/code/tables/files_uploads.md) (`token`, `analyst_id`, `folder_id`, `item_id`, `file_name`, `size_bytes`, `created_datetime`) |  |
| 79 |  | read [files_uploads](/code/tables/files_uploads.md) |  |
| 101 |  | **update** [files_uploads](/code/tables/files_uploads.md) (`received_bytes`, `updated_datetime`) |  |
| 108 |  | **delete** [files_uploads](/code/tables/files_uploads.md) |  |
| 137 |  | **insert** [files_items](/code/tables/files_items.md) (`folder_id`, `name`, `size_bytes`, `mime_type`, `created_by`, `created_datetime`) |  |
| 143 |  | read [files_versions](/code/tables/files_versions.md) |  |
| 145 |  | **insert** [files_versions](/code/tables/files_versions.md) (`item_id`, `version_no`, `storage_path`, `size_bytes`, `mime_type`, `sha256`, `uploaded_by`, `uploaded_datetime`) |  |
| 149 |  | **update** [files_items](/code/tables/files_items.md) (`current_version_id`, `size_bytes`, `mime_type`) |  |
| 152 |  | **delete** [files_uploads](/code/tables/files_uploads.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiBody()](/code/functions/filesApiBody.md), [filesApiFail()](/code/functions/filesApiFail.md), [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesAudit()](/code/functions/filesAudit.md), [filesCleanName()](/code/functions/filesCleanName.md), [filesDetectMime()](/code/functions/filesDetectMime.md), [filesFreeName()](/code/functions/filesFreeName.md), [filesIncomingPath()](/code/functions/filesIncomingPath.md), [filesItem()](/code/functions/filesItem.md), [filesMaxUploadBytes()](/code/functions/filesMaxUploadBytes.md), [filesNameTaken()](/code/functions/filesNameTaken.md), [filesNeed()](/code/functions/filesNeed.md), [filesNewStoragePath()](/code/functions/filesNewStoragePath.md), [filesStorageRoot()](/code/functions/filesStorageRoot.md), [uploadFormatBytes()](/code/functions/uploadFormatBytes.md)

