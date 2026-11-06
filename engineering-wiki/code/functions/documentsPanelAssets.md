# documentsPanelAssets()

Load the panel's stylesheet and script once per page.

Separate from rendering so a page that mounts the panel itself — one whose
record changes in JavaScript, like the asset detail view — can still get the
assets without a server-rendered container.

```php
function documentsPanelAssets(string $pathPrefix = '../'): void
```

Defined in [includes/documents_panel.php](/code/files/includes/documents_panel.php.md), line 32.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$pathPrefix` | `string` | `'../'` | '' from the web root, '../' from a module folder - put before the asset URLs |

**Returns:** `void`

## Called from

- [asset-management/index.php](/code/files/asset-management/index.php.md)
- [includes/documents_panel.php](/code/files/includes/documents_panel.php.md) - in [renderDocumentsPanel()](/code/functions/renderDocumentsPanel.md)
- [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md) - in [renderWaffleMenuJS()](/code/functions/renderWaffleMenuJS.md)
- [projects/view.php](/code/files/projects/view.php.md)

