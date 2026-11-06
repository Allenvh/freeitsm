# tenancyColumnExists()

Does `$table` have `$column`? Cached per request; false if the table is missing.

⚠️ Callers read `false` as "not migrated yet, so allow" — `analystCanAccessArticle()`
returns true outright on it. That makes a bare catch fail open, so only a genuinely
missing table answers false here. On any other error we answer true, which sends the
caller into its own try/catch, where the query fails and `tenancyDegradeAllowed()`
denies. Uncached in that case, so a blip does not pin the answer.

```php
function tenancyColumnExists(PDO $conn, string $table, string $column): bool
```

Defined in [includes/tenancy.php](/code/files/includes/tenancy.php.md), line 922.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the table name - a developer literal (backticks are stripped) |
| `$column` | `string` |  | the column to look for |

**Returns:** `bool`

## Calls

[dbErrorIsMissingTable()](/code/functions/dbErrorIsMissingTable.md)

## Called from

- [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md)
- [includes/asset_locations.php](/code/files/includes/asset_locations.php.md) - in [assetLocationsSharedReady()](/code/functions/assetLocationsSharedReady.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in `KnowledgeViewer::scopeForOneCompany`, `KnowledgeViewer::scopeForAllAccessible`, [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)
- [includes/lms/knowledge_source.php](/code/files/includes/lms/knowledge_source.php.md) - in [lmsSharedOnlySql()](/code/functions/lmsSharedOnlySql.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [knowledgeTenantFilterForCompany()](/code/functions/knowledgeTenantFilterForCompany.md), [analystCanAccessArticle()](/code/functions/analystCanAccessArticle.md)

