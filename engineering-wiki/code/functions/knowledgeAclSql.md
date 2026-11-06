# knowledgeAclSql()

The access-list clause. Returns ['', []] until the folder/ACL tables exist, which is why introducing this file changes nothing.

── The plan, recorded so it is not re-derived later ──────────────────────────
README states MySQL 8.0+, and api/tickets/get_emails.php already depends on a
window function, so `WITH RECURSIVE` IS available. The effective permission
is therefore computed by walking the folder chain in SQL at read time, NOT by
maintaining a denormalised effective-ACL table — a cache of a permission is a
second source of truth about who can see what, and it is wrong every time the
rebuild is missed.

The walk climbs from the article's folder to the first ancestor that does not
inherit (or the root), and the node it stops on supplies the polarity:

  Open       readable unless a DENY matches one of the viewer's principals
  Restricted readable only if a GRANT matches one

Because polarity lives on the object and never on the row, an allow and a
deny cannot coexist — there is no precedence rule to encode here.

Two behaviours still to implement alongside the tables:
  • knowledge_folder_permission_model — 'containers' (default; EVERY ancestor
    must be readable) vs 'filing' (the article's own list is authoritative).
    It is a branch INSIDE this function, which is why it must ship with the
    first version rather than being layered on later.
  • the knowledge.admin floor — always passes, and writes an audit row.

```php
function knowledgeAclSql(
    PDO $conn,
    KnowledgeViewer $viewer,
    string $alias = "a",
    array $opts = []
): array
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 540.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$viewer` | `KnowledgeViewer` |  | who is reading |
| `$alias` | `string` | `"a"` | the knowledge_articles alias in the calling query; '' when it has none |
| `$opts` | `array` | `[]` | no_admin_floor (true) leaves out the administrator floor; other keys are ignored here |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 599 | `knowledgeAclSql` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[knowledgeAclHasAnyRows()](/code/functions/knowledgeAclHasAnyRows.md), [knowledgeAclIndex()](/code/functions/knowledgeAclIndex.md), [knowledgeAclTablesExist()](/code/functions/knowledgeAclTablesExist.md), [knowledgeFolderIndex()](/code/functions/knowledgeFolderIndex.md), [knowledgeFolderPermissionModel()](/code/functions/knowledgeFolderPermissionModel.md), [knowledgeFolderReadable()](/code/functions/knowledgeFolderReadable.md), [knowledgeNodePermits()](/code/functions/knowledgeNodePermits.md), [knowledgeViewerHasAdminFloor()](/code/functions/knowledgeViewerHasAdminFloor.md), [knowledgeViewerPrincipals()](/code/functions/knowledgeViewerPrincipals.md)

## Called from

- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

