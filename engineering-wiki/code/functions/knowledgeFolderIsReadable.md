# knowledgeFolderIsReadable()

May this viewer reach this folder? Delegates; never re-implements the rule.

```php
function knowledgeFolderIsReadable(PDO $conn, KnowledgeViewer $viewer, int $folderId): bool
```

Defined in [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), line 141.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$viewer` | `KnowledgeViewer` |  | who is looking, as built by KnowledgeViewer::forAnalyst() |
| `$folderId` | `int` |  | the knowledge_folders id to check |

**Returns:** `bool`

## Calls

[knowledgeAclHasAnyRows()](/code/functions/knowledgeAclHasAnyRows.md), [knowledgeAclIndex()](/code/functions/knowledgeAclIndex.md), [knowledgeFolderIndex()](/code/functions/knowledgeFolderIndex.md), [knowledgeFolderPermissionModel()](/code/functions/knowledgeFolderPermissionModel.md), [knowledgeFolderReadable()](/code/functions/knowledgeFolderReadable.md), [knowledgeViewerHasAdminFloor()](/code/functions/knowledgeViewerHasAdminFloor.md), [knowledgeViewerPrincipals()](/code/functions/knowledgeViewerPrincipals.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [handleList()](/code/functions/handleList.md), [requireFolderAccess()](/code/functions/requireFolderAccess.md)

