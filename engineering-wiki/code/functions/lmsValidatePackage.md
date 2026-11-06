# lmsValidatePackage()

Check every entry in the archive. Returns the entries that should be written (directories excluded), or throws with a message naming the offending file.

```php
function lmsValidatePackage(ZipArchive $zip): array
```

Defined in [includes/lms_package.php](/code/files/includes/lms_package.php.md), line 66.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$zip` | `ZipArchive` |  | the uploaded SCORM package, already opened - untrusted |

**Returns:** array<int,string> Entry names, safe to extract.

## Called from

- [api/lms/courses.php](/code/files/api/lms/courses.php.md)

