# handleAddShortcut()

Make an article appear in a second folder.

🔑 A SHORTCUT HAS NO PERMISSIONS OF ITS OWN. It resolves to the target and the
target's rules decide — which is exactly what lets the tree stay single-parent
(one home, one answer to "who can see it") while a document still appears in
two places. Two consequences, both load-bearing:

  • a shortcut can never GRANT. If you cannot read the target, the shortcut is
    not a way in — the list query filters on the TARGET, so the row simply is
    not returned rather than being returned and hidden.
  • creating one requires being able to read the target, or a shortcut would
    be a way to confirm that an article you cannot see exists.

```php
function handleAddShortcut(PDO $conn, int $analystId, array $in): void
```

Defined in [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), line 354.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the decoded JSON request: article_id and the folder_id the shortcut goes in |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 373 | `handleAddShortcut` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 382 | `handleAddShortcut` | **insert** [knowledge_shortcuts](/code/tables/knowledge_shortcuts.md) (`folder_id`, `article_id`, `created_by_id`, `created_datetime`) |  |

## Calls

[knowledgeAudit()](/code/functions/knowledgeAudit.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md), [requireFolderAccess()](/code/functions/requireFolderAccess.md)

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md)

