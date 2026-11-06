# filesDetectMime()

The mime type we record for a stored file: sniffed, else guessed from the name.

```php
function filesDetectMime(string $absPath, string $name): string
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 131.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$absPath` | `string` |  | the full path of the file on disk, whose content is sniffed |
| `$name` | `string` |  | the file's display name, whose extension is the fallback guess |

**Returns:** `string`

## Calls

[attachmentServeRules()](/code/functions/attachmentServeRules.md), [uploadDetectMime()](/code/functions/uploadDetectMime.md)

## Called from

- [api/files/upload.php](/code/files/api/files/upload.php.md)

