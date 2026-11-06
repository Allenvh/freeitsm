# knowledgeViewerPrincipals()

Everything the viewer counts as, as "type:id" strings for cheap lookup.

An analyst is themselves plus their teams; a portal user is themselves. Both
pick up any user group they belong to whose membership has not expired —
⚠️ the expiry is applied HERE, at read time, so the three engineers who were
given a folder for a week lose it by the clock rather than by somebody
remembering to take it away.

```php
function knowledgeViewerPrincipals(PDO $conn, KnowledgeViewer $viewer): array
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 721.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$viewer` | `KnowledgeViewer` |  | who is reading |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 732 | `knowledgeViewerPrincipals` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 746 | `knowledgeViewerPrincipals` | read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md), read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |

## Called from

- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [knowledgeFolderIsReadable()](/code/functions/knowledgeFolderIsReadable.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeAclSql()](/code/functions/knowledgeAclSql.md)

