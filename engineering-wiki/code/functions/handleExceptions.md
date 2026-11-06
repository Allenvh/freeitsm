# handleExceptions()

Everything on this install carrying its OWN permissions rather than its parent's — the report from §9 of the design page.

⚠️ THIS IS THE ANSWER TO THE ONE THING THAT MAKES PERMISSION SYSTEMS
UNMANAGEABLE. A document whose rules differ from its folder is invisible from
the tree: you cannot look at a folder and know what is true inside it. At ten
exceptions that is fine and at four hundred it is not, so the exceptions get a
list of their own. Nothing here is a permission check — it is a way of finding
the things that need checking.

```php
function handleExceptions(PDO $conn, int $analystId): void
```

Defined in [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), line 422.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 433 | `handleExceptions` | read [knowledge_acl](/code/tables/knowledge_acl.md), read [knowledge_folders](/code/tables/knowledge_folders.md) |  |
| 450 | `handleExceptions` | read [knowledge_acl](/code/tables/knowledge_acl.md), read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[knowledgeViewerHasAdminFloor()](/code/functions/knowledgeViewerHasAdminFloor.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md)

