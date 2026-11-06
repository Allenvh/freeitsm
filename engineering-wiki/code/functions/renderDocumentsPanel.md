# renderDocumentsPanel()

Renders the attached-documents panel for one record, loading its CSS and JS once.

Renders nothing for an unknown record type, or for an unsaved record (id 0)
unless `$jsVar` is given so the page can point it at a record later.

```php
function renderDocumentsPanel(
    string $parentType,
    int $parentId,
    string $pathPrefix = '../',
    bool $canEdit = true,
    string $jsVar = ''
): void
```

Defined in [includes/documents_panel.php](/code/files/includes/documents_panel.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$parentType` | `string` |  | a key from `documentEntityRegistry()` |
| `$parentId` | `int` |  | the record's id. 0 mounts an EMPTY panel, for a page that will call panel.setParent() once the user picks a record — pass a `$jsVar` to capture the instance. |
| `$pathPrefix` | `string` | `'../'` | '' from the web root, '../' from a module folder |
| `$canEdit` | `bool` | `true` | false renders a read-only list |
| `$jsVar` | `string` | `''` | optional global to hold the mounted panel, so a JS-driven page can re-point it |

**Returns:** `void`

## Calls

[documentEntityDef()](/code/functions/documentEntityDef.md), [documentsPanelAssets()](/code/functions/documentsPanelAssets.md)

## Called from

- [asset-management/leases/view.php](/code/files/asset-management/leases/view.php.md)
- [contracts/edit.php](/code/files/contracts/edit.php.md)
- [domains/view.php](/code/files/domains/view.php.md)

