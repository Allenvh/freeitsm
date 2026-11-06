# includes/uploads.php

> PHP - 1005 lines

Safe file uploads — the ONE place the rules live.

⚠️ WHY THIS EXISTS. Change management accepted an upload with no validation
at all, kept the caller's filename, and wrote it into a web-reachable folder.
A file called `probe.php` could be uploaded and then fetched over HTTP, and it
EXECUTED. That is remote code execution by anyone who could attach a file to a
change. It was found while building form file uploads, where the same pattern
would have handed the hole to self-service customers rather than analysts.

Three defences, and the point is that NO ONE of them is trusted alone:

  1. an extension AND mime whitelist — a renamed `.php` fails the mime check,
     and a file whose bytes look like a PNG still fails if it is named .php;
  2. a RANDOM stored filename with an extension drawn from our own whitelist,
     never from the caller's string. The original name is kept in the database
     for display only, so nothing user-controlled reaches the filesystem;
  3. an .htaccess that disables execution in the upload directory, so even a
     future endpoint that forgets 1 and 2 does not become an RCE.

🔑 Files are still served through an authorising PHP endpoint. The directory
protection is the net under the tightrope, not the tightrope.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [uploadStoreFile()](/code/functions/uploadStoreFile.md) | 142 | Validate one `$_FILES` entry and move it somewhere safe. |
| [uploadPrepareDir()](/code/functions/uploadPrepareDir.md) | 224 | Create an upload directory and drop execution protection into it. |
| [uploadPrepareWebServableDir()](/code/functions/uploadPrepareWebServableDir.md) | 369 | Prepare a directory whose contents the BROWSER IS MEANT TO FETCH. |
| [htaccessUnguardedPhpDirectives()](/code/functions/htaccessUnguardedPhpDirectives.md) | 500 | Line numbers (1-based) of every php_flag / php_value / php_admin_* directive in an .htaccess that is NOT inside an <IfModule> for mod_php. |
| [htaccessGuardPhpDirectives()](/code/functions/htaccessGuardPhpDirectives.md) | 536 | Wrap every unguarded PHP directive in an .htaccess in <IfModule> blocks for mod_php 8 (mod_php.c) and mod_php 7 (mod_php7.c), keeping its indentation and the file's own line endings. Returns the text unchanged when there is nothing to do, so it is safe to run on every file every time. |
| [uploadRepairHtaccessFile()](/code/functions/uploadRepairHtaccessFile.md) | 558 | Repair one .htaccess in place. True if it was changed. |
| [uploadRepairHtaccessTree()](/code/functions/uploadRepairHtaccessTree.md) | 577 | Repair every .htaccess under the upload folders. Returns how many changed. |
| [uploadCleanName()](/code/functions/uploadCleanName.md) | 607 | The original filename, reduced to something safe to STORE AND DISPLAY. |
| [uploadDetectMime()](/code/functions/uploadDetectMime.md) | 625 | The file's real mime type, or null if this server cannot tell us. |
| [uploadErrorMessage()](/code/functions/uploadErrorMessage.md) | 644 | PHP's upload error codes as something a person can act on. |
| [uploadFormatBytes()](/code/functions/uploadFormatBytes.md) | 663 | A byte count as readable text - "1.5 MB", "300 KB" or "512 bytes". |
| [attachmentServeRules()](/code/functions/attachmentServeRules.md) | 738 | Decide how to serve a stored attachment, from its filename alone. |
| [attachmentSendHeaders()](/code/functions/attachmentSendHeaders.md) | 758 | Emit the Content-Type / Content-Disposition / nosniff headers for an attachment. Callers still do their own authorisation and file-existence checks first. |
| [uploadStoreBytes()](/code/functions/uploadStoreBytes.md) | 820 | Store ingested bytes safely, applying the install's policy for types we do not accept. |
| [uploadDetectMimeFromBytes()](/code/functions/uploadDetectMimeFromBytes.md) | 904 | The real mime type of bytes held in memory, or null if this server cannot tell. |
| [attachmentRejectPolicy()](/code/functions/attachmentRejectPolicy.md) | 923 | The install's policy for an attachment whose type we do not accept. |
| [attachmentAllowedTypes()](/code/functions/attachmentAllowedTypes.md) | 953 | Which attachment types this install actually accepts. |
| [attachmentIngestNotice()](/code/functions/attachmentIngestNotice.md) | 986 | The block appended to a message body when attachments were not accepted, so the outcome is visible on the ticket instead of a file quietly vanishing. Returns '' when there is nothing to say. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 928 | `attachmentRejectPolicy` | read [system_settings](/code/tables/system_settings.md) |  |
| 959 | `attachmentAllowedTypes` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [includes/attachment_limits.php](/code/files/includes/attachment_limits.php.md)

**Included by:** [api/assets/import_upload.php](/code/files/api/assets/import_upload.php.md), [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md), [api/change-management/upload_attachment.php](/code/files/api/change-management/upload_attachment.php.md), [api/documents/download.php](/code/files/api/documents/download.php.md), [api/documents/save.php](/code/files/api/documents/save.php.md), [api/forms/image.php](/code/files/api/forms/image.php.md), [api/forms/upload_image.php](/code/files/api/forms/upload_image.php.md), [api/messaging/media.php](/code/files/api/messaging/media.php.md), [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md), [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md), [api/self-service/get_article_document.php](/code/files/api/self-service/get_article_document.php.md), [api/self-service/get_attachment.php](/code/files/api/self-service/get_attachment.php.md), [api/self-service/get_document.php](/code/files/api/self-service/get_document.php.md), [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md), [api/system/db_verify.php](/code/files/api/system/db_verify.php.md), [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md), [api/system/save_branding.php](/code/files/api/system/save_branding.php.md), [api/system/self_service_portal.php](/code/files/api/system/self_service_portal.php.md), [api/system/tenant_logo.php](/code/files/api/system/tenant_logo.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [api/tickets/get_attachment.php](/code/files/api/tickets/get_attachment.php.md), [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), [includes/files/storage.php](/code/files/includes/files/storage.php.md), [includes/lms_package.php](/code/files/includes/lms_package.php.md), [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), [includes/warroom.php](/code/files/includes/warroom.php.md)

**Calls:** [attachmentMaxBytes()](/code/functions/attachmentMaxBytes.md)

