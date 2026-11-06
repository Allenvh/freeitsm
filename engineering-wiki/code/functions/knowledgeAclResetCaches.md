# knowledgeAclResetCaches()

Forget everything memoised about folders and the access list.

Call after CHANGING permissions inside a single process — the tests do this
constantly, and any future admin screen that edits an access list and then
re-renders in the same request must too, or it will show the state it just
replaced.

```php
function knowledgeAclResetCaches(): void
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 809.

**Returns:** `void`

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [handleCreate()](/code/functions/handleCreate.md), [handleMove()](/code/functions/handleMove.md), [handleDelete()](/code/functions/handleDelete.md)
- [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md) - in [handlePreview()](/code/functions/handlePreview.md), [handleSet()](/code/functions/handleSet.md)
- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) - in [handleSetMode()](/code/functions/handleSetMode.md), [handleAdd()](/code/functions/handleAdd.md), [handleRemove()](/code/functions/handleRemove.md)

