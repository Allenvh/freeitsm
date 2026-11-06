# api/documents/save.php

> API endpoint - 169 lines

API Endpoint: attach a document to something.

Two kinds, one endpoint, because to the person using it they are one list:
  - file : multipart POST with `document`, stored through `uploadStoreFile()`
  - link : JSON or form POST with `external_url`, nothing stored at all

⚠️ THE ACCESS CHECK RUNS FIRST, BEFORE ANY FILE IS WRITTEN. Change management
learned this the hard way: its upload checked access AFTER `move_uploaded_file()`,
so somebody with no rights to the record could still land a file on the server
— the check stopped the database row, not the write.

You may attach to a parent only if you can already SEE that parent. Which, given
documents inherit visibility from their parents, is also what stops attaching
being a way to launder a document into somewhere you can read.

## The endpoint

| | |
|---|---|
| URL | `api/documents/save.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php); Signed-in analyst (session check) (via includes/service_context.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 125 |  | **insert** [documents](/code/tables/documents.md) (`kind`, `title`, `description`, `storage_key`, `original_name`, `mime_type`, `size_bytes`, `content_hash`, `external_url`, `tenant_id`, `uploaded_by_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/uploads.php](/code/files/includes/uploads.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md)

**Calls:** [attachmentAllowedTypes()](/code/functions/attachmentAllowedTypes.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [documentCanViewParent()](/code/functions/documentCanViewParent.md), [documentEntityDef()](/code/functions/documentEntityDef.md), [documentInsertLink()](/code/functions/documentInsertLink.md), [documentStorageKey()](/code/functions/documentStorageKey.md), [documentTextDrain()](/code/functions/documentTextDrain.md), [documentTextEnqueue()](/code/functions/documentTextEnqueue.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [searchIndexDocument()](/code/functions/searchIndexDocument.md), [uploadStoreFile()](/code/functions/uploadStoreFile.md)

