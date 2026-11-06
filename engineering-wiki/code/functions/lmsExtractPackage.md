# lmsExtractPackage()

Extract only the entries that passed validation, one at a time.

extractTo(`$dir`) with no second argument is what we are avoiding: it writes
everything, including anything validation would have rejected. Passing the
vetted list means the set of files on disk is exactly the set we approved.

```php
function lmsExtractPackage(ZipArchive $zip, string $destDir, array $files): void
```

Defined in [includes/lms_package.php](/code/files/includes/lms_package.php.md), line 139.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$zip` | `ZipArchive` |  | the opened SCORM package |
| `$destDir` | `string` |  | the folder to unpack into |
| `$files` | `array` |  | the entry names `lmsValidatePackage()` approved; nothing else is written |

**Returns:** `void`

## Called from

- [api/lms/courses.php](/code/files/api/lms/courses.php.md)

