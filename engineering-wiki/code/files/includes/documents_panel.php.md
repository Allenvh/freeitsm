# includes/documents_panel.php

> PHP - 96 lines

Render the documents panel on any record.

A module adds attachments with one call:

    require_once __DIR__ . '/../includes/documents_panel.php';
    renderDocumentsPanel('contract', `$contractId`, '../');

That is the whole integration. The module does not learn how documents are
stored, served or authorised, and includes/documents.php does not learn
anything about the module — the only coupling is the entity type string, which
must exist in `documentEntityRegistry()`.

⚠️ The panel loads its own list over the API rather than being handed rows, so
the permission check happens server-side on every view. A page that fetched
the documents itself and passed them in would be one forgotten filter away
from showing somebody a document they cannot open.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [documentsPanelAssets()](/code/functions/documentsPanelAssets.md) | 32 | Load the panel's stylesheet and script once per page. |
| [renderDocumentsPanel()](/code/functions/renderDocumentsPanel.md) | 56 | Renders the attached-documents panel for one record, loading its CSS and JS once. |

## How it hangs together

**Includes:** [includes/documents.php](/code/files/includes/documents.php.md)

**Included by:** [asset-management/index.php](/code/files/asset-management/index.php.md), [asset-management/leases/view.php](/code/files/asset-management/leases/view.php.md), [contracts/edit.php](/code/files/contracts/edit.php.md), [domains/view.php](/code/files/domains/view.php.md), [includes/waffle-menu.php](/code/files/includes/waffle-menu.php.md), [projects/view.php](/code/files/projects/view.php.md)

**Calls:** [documentEntityDef()](/code/functions/documentEntityDef.md)

