# knowledgeFolderReadable()

May this viewer reach `$folderId`?

containers — EVERY node from the folder up to the root must permit. A node
  that inherits has no rules of its own and therefore permits trivially, which
  is what makes inheritance fall out of the walk rather than needing its own
  pass: the nearest non-inheriting ancestor is the only one that can object.
filing — only the NEAREST non-inheriting node speaks.

A cycle (which the parent FK does not prevent) terminates the walk rather than
hanging: a folder that is its own ancestor is corrupt, and the safe reading of
corrupt is "not readable".

```php
function knowledgeFolderReadable(
    int $folderId,
    array $folders,
    array $acl,
    array $principals,
    string $mode
): bool
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 688.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$folderId` | `int` |  | the knowledge_folders id to check |
| `$folders` | `array` |  | the folder tree from `knowledgeFolderIndex()`, [id => [parent, restricted, inherit]] |
| `$acl` | `array` |  | the access rows from `knowledgeAclIndex()` |
| `$principals` | `array` |  | the viewer's 'type:id' strings from `knowledgeViewerPrincipals()` |
| `$mode` | `string` |  | 'containers' (every ancestor must permit) or 'filing' (the nearest rule-bearing node decides) |

**Returns:** `bool`

## Calls

[knowledgeNodePermits()](/code/functions/knowledgeNodePermits.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [knowledgeFolderIsReadable()](/code/functions/knowledgeFolderIsReadable.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeAclSql()](/code/functions/knowledgeAclSql.md)

