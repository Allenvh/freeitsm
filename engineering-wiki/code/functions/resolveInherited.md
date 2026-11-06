# resolveInherited()

Walk up to the folder whose rules actually apply, and describe them.

Returns null when nothing above restricts anything — which is the ordinary
case and deserves saying plainly rather than showing an empty list, because an
empty list and "no restrictions anywhere" look identical and mean opposite
things.

```php
function resolveInherited(PDO $conn, string $type, int $id): ?array
```

Defined in [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), line 159.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$type` | `string` |  | 'folder' or 'article' |
| `$id` | `int` |  | that folder's or article's id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 165 | `resolveInherited` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 179 | `resolveInherited` | read [knowledge_folders](/code/tables/knowledge_folders.md) |  |
| 183 | `resolveInherited` | read [knowledge_acl](/code/tables/knowledge_acl.md) |  |

## Calls

[knowledgeFolderIndex()](/code/functions/knowledgeFolderIndex.md), [principalName()](/code/functions/principalName.md)

## Called from

- [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) - in [handleGet()](/code/functions/handleGet.md)

