# documentEntityDef()

Returns one record type's registry entry (label, URL, title column, access checks), or null when unknown.

```php
function documentEntityDef(string $type): ?array
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 350.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | an entity type key from `documentEntityRegistry()`, e.g. 'contract' - may be untrusted |

**Returns:** `?array`

## Calls

[documentEntityRegistry()](/code/functions/documentEntityRegistry.md)

## Called from

- [api/documents/attach.php](/code/files/api/documents/attach.php.md)
- [api/documents/find.php](/code/files/api/documents/find.php.md)
- [api/documents/list.php](/code/files/api/documents/list.php.md)
- [api/documents/save.php](/code/files/api/documents/save.php.md)
- [api/documents/unlink.php](/code/files/api/documents/unlink.php.md)
- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentCanViewParent()](/code/functions/documentCanViewParent.md), [documentVisibilityClause()](/code/functions/documentVisibilityClause.md), [documentParentName()](/code/functions/documentParentName.md), [documentVisibleParents()](/code/functions/documentVisibleParents.md), [documentsDetachParent()](/code/functions/documentsDetachParent.md)
- [includes/documents_panel.php](/code/files/includes/documents_panel.php.md) - in [renderDocumentsPanel()](/code/functions/renderDocumentsPanel.md)

