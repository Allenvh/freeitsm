# documentStoragePath()

Absolute path for a stored document.

🔑 The database stores an opaque KEY, never a path. A path baked into a row is
a data migration the day the files move to another disk, a NAS or object
storage; a key is a change to this one function.

```php
function documentStoragePath(string $storageKey): string
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 736.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$storageKey` | `string` |  | documents.storage_key, as made by `documentStorageKey()`; any "../" is stripped |

**Returns:** `string`

## Called from

- [api/documents/download.php](/code/files/api/documents/download.php.md)
- [api/documents/unlink.php](/code/files/api/documents/unlink.php.md)
- [api/self-service/get_article_document.php](/code/files/api/self-service/get_article_document.php.md)
- [api/self-service/get_document.php](/code/files/api/self-service/get_document.php.md)
- [api/system/debug-tools/D012_ticket_attachments.php](/code/files/api/system/debug-tools/D012_ticket_attachments.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentsCollectOrphans()](/code/functions/documentsCollectOrphans.md)
- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [documentTextDrain()](/code/functions/documentTextDrain.md)

