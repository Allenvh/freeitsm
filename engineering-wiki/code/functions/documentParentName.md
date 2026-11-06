# documentParentName()

The display name of one parent record.

🔑 ONE implementation. This query was written out four separate times — in
`documentVisibleParents()`, list.php and twice in global_search.php — which is
how a registry gains a fifth caller that quietly does it differently.

⚠️ `title` is a COLUMN NAME on the parent's own table, which covers eleven of
the twelve entity types. `title_sql` is the escape hatch for the twelfth: a
software licence has no name of its own — it lives in software_inventory_apps
via app_id — so its entry supplies a scalar subquery instead. Both come from
the registry, which is code, so neither is ever attacker-supplied.

```php
function documentParentName(PDO $conn, string $type, int $parentId): ?string
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 523.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$type` | `string` |  | the parent's entity type key from `documentEntityRegistry()`; unknown gives null |
| `$parentId` | `int` |  | the parent record's id in that type's table |

**Returns:** `?string`

## Calls

[documentEntityDef()](/code/functions/documentEntityDef.md)

## Called from

- [api/documents/list.php](/code/files/api/documents/list.php.md)
- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentVisibleParents()](/code/functions/documentVisibleParents.md)

