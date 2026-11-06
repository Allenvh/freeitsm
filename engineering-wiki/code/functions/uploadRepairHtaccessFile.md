# uploadRepairHtaccessFile()

Repair one .htaccess in place. True if it was changed.

```php
function uploadRepairHtaccessFile(string $path): bool
```

Defined in [includes/uploads.php](/code/files/includes/uploads.php.md), line 558.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$path` | `string` |  | absolute path of one .htaccess file; an unreadable file is left alone |

**Returns:** `bool`

## Calls

[htaccessGuardPhpDirectives()](/code/functions/htaccessGuardPhpDirectives.md)

## Called from

- [includes/lms_package.php](/code/files/includes/lms_package.php.md) - in [lmsHardenContentDir()](/code/functions/lmsHardenContentDir.md)
- [includes/uploads.php](/code/files/includes/uploads.php.md) - in [uploadPrepareDir()](/code/functions/uploadPrepareDir.md), [uploadPrepareWebServableDir()](/code/functions/uploadPrepareWebServableDir.md), [uploadRepairHtaccessTree()](/code/functions/uploadRepairHtaccessTree.md)

