# knowledgeAclHasAnyRows()

Is ANYTHING restricted on this install? The cheap test that skips all the work.

⚠️ IT IS NOT ENOUGH TO ASK WHETHER knowledge_acl HAS ROWS, and that mistake
shipped for exactly one afternoon. A **Restricted object with an empty list**
is a real restriction — it means "restricted to nobody" — and it has no access
rows at all. Asking only about rows meant that emptying a list (or flipping an
Open object to Restricted, which WIPES the list by design) could leave
knowledge_acl empty, take this fast path, and hand the object back to
everybody. The most restrictive state in the model was the one that turned
the guard off.

Found by a harness that drove the real modal rather than a fixture that always
had rows in it — the unit-shaped test passed throughout.

```php
function knowledgeAclHasAnyRows(PDO $conn): bool
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 842.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 847 | `knowledgeAclHasAnyRows` | read [knowledge_acl](/code/tables/knowledge_acl.md) |  |
| 848 | `knowledgeAclHasAnyRows` | read [knowledge_folders](/code/tables/knowledge_folders.md) |  |
| 849 | `knowledgeAclHasAnyRows` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [knowledgeFolderIsReadable()](/code/functions/knowledgeFolderIsReadable.md)
- [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md) - in [handlePreview()](/code/functions/handlePreview.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeAclSql()](/code/functions/knowledgeAclSql.md)

