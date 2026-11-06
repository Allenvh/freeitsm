# dbVerifyIndexListSelfCheck()

Compare the committed generated list against a fresh parse of freeitsm.sql. Returns human-readable problem strings; an empty array means they're in sync.

Skips silently if freeitsm.sql isn't present (a trimmed deployment may not ship
it) — we never cry drift when we can't see the source of truth.

```php
function dbVerifyIndexListSelfCheck(?string $sqlPath = null, ?string $listPath = null): array
```

Defined in [includes/db_verify_index_parse.php](/code/files/includes/db_verify_index_parse.php.md), line 75.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$sqlPath` | `?string` | `null` | path to freeitsm.sql; null means database/freeitsm.sql in this install |
| `$listPath` | `?string` | `null` | path to the generated index list; null means includes/db_verify_indexes.php |

**Returns:** `array`

## Calls

[dbVerifyIndexTypeOf()](/code/functions/dbVerifyIndexTypeOf.md), [dbVerifyParseIndexesFromSql()](/code/functions/dbVerifyParseIndexesFromSql.md)

## Called from

- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)

