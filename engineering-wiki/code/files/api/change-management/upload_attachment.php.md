# api/change-management/upload_attachment.php

> API endpoint - 98 lines

API Endpoint: Upload file attachment to a change

⚠️ SECURITY-CRITICAL, and it was wrong. This endpoint used to accept any file
with no validation, keep the caller's own filename, and write it into
change-management/attachments/ — a folder Apache serves. Uploading `x.php`
and then requesting it over HTTP executed it. Verified, not theorised.

Two rules now, and both matter:

  1. every byte goes through `uploadStoreFile()`, which whitelists extension AND
     content type, invents its own filename, and drops execution protection
     into the directory;
  2. the ACCESS CHECK RUNS FIRST. It used to run after `move_uploaded_file()`,
     so an analyst with no rights to a change could still land a file on the
     server — the check only stopped the database row, not the write.

Downloads already go through get_attachment.php, which authorises. Nothing
here is ever meant to be fetched by direct URL.

## The endpoint

| | |
|---|---|
| URL | `api/change-management/upload_attachment.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Reads | `POST change_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 |  | **insert** [change_attachments](/code/tables/change_attachments.md) (`change_id`, `file_name`, `file_path`, `file_size`, `file_type`, `uploaded_by_id`, `uploaded_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md)

**Calls:** [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [uploadStoreFile()](/code/functions/uploadStoreFile.md)

