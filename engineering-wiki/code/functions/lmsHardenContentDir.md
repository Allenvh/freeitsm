# lmsHardenContentDir()

Stop the content tree ever executing code, whatever ends up in it.

Belt and braces behind the allowlist: if a future change (or a bug) lets a
script through, Apache still refuses to run it. Written once, next to the
courses. Needs AllowOverride to be on — which is why it is the SECOND line of
defence and not the first.

```php
function lmsHardenContentDir(string $contentRoot): void
```

Defined in [includes/lms_package.php](/code/files/includes/lms_package.php.md), line 156.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$contentRoot` | `string` |  | the folder that holds every unpacked course; the .htaccess is written here |

**Returns:** `void`

## Calls

[uploadRepairHtaccessFile()](/code/functions/uploadRepairHtaccessFile.md)

## Called from

- [api/lms/courses.php](/code/files/api/lms/courses.php.md)

