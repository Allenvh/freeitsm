# knowledgeAclTablesExist()

Do the folder/access-list tables exist yet?

Mirrors `tenancyColumnExists()`: cached per request, and on an UNEXPECTED error
it assumes the tables ARE present so the guard still runs. A probe that fails
open would drop the access list on exactly the kind of database hiccup where
you least want it dropped.

```php
function knowledgeAclTablesExist(PDO $conn): bool
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 901.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Called from

- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeAclSql()](/code/functions/knowledgeAclSql.md)

