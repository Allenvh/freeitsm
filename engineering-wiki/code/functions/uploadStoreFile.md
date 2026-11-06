# uploadStoreFile()

Validate one `$_FILES` entry and move it somewhere safe.

```php
function uploadStoreFile(
    array $file,
    string $destDir,
    array $allowed = UPLOAD_TYPES_ATTACHMENT,
    ?int $maxBytes = null
): array
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 142.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$file` | `array` |  | one entry from `$_FILES` |
| `$destDir` | `string` |  | absolute directory; created (and protected) if absent |
| `$allowed` | `array` | `UPLOAD_TYPES_ATTACHMENT` | ext => [mime, …]; defaults to the everyday set |
| `$maxBytes` | `?int` | `null` | null = the configured limit (attachmentMaxBytes()) |

**Returns:** array ['stored_name'=>…, 'original_name'=>…, 'size'=>int, 'mime'=>string, 'path'=>absolute]

## Calls

[attachmentMaxBytes()](/code/functions/attachmentMaxBytes.md), [uploadCleanName()](/code/functions/uploadCleanName.md), [uploadDetectMime()](/code/functions/uploadDetectMime.md), [uploadErrorMessage()](/code/functions/uploadErrorMessage.md), [uploadFormatBytes()](/code/functions/uploadFormatBytes.md), [uploadPrepareDir()](/code/functions/uploadPrepareDir.md)

## Called from

- [api/assets/import_upload.php](/code/files/api/assets/import_upload.php.md)
- [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md)
- [api/change-management/upload_attachment.php](/code/files/api/change-management/upload_attachment.php.md)
- [api/documents/save.php](/code/files/api/documents/save.php.md)
- [api/forms/upload_image.php](/code/files/api/forms/upload_image.php.md)
- [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md)
- [api/system/save_branding.php](/code/files/api/system/save_branding.php.md)
- [api/system/self_service_portal.php](/code/files/api/system/self_service_portal.php.md)
- [api/system/tenant_logo.php](/code/files/api/system/tenant_logo.php.md)
- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomStoreAttachment()](/code/functions/warRoomStoreAttachment.md)

