# uploadRepairHtaccessTree()

Repair every .htaccess under the upload folders. Returns how many changed.

The committed .htaccess files are fixed by updating FreeITSM, but the ones it
WROTE itself — one per ticket attachment folder, the portal logo folder,
per-form image folders — stay as they were written until something rewrites
them. Database Verify calls this so an upgraded install is repaired in one go.

```php
function uploadRepairHtaccessTree(string $appRoot): int
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 577.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$appRoot` | `string` |  | absolute path of the FreeITSM folder; UPLOAD_HTACCESS_ROOTS are relative to it |

**Returns:** `int`

## Calls

[uploadRepairHtaccessFile()](/code/functions/uploadRepairHtaccessFile.md)

## Called from

- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)

