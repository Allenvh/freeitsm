# dbVerifyColumnSelfCheck()

Compare includes/db_verify_schema.php against a fresh parse of freeitsm.sql. Returns human-readable problem strings; empty means they're in sync.

Skips silently if freeitsm.sql isn't present (a trimmed deployment may not
ship it) — never cry drift when the source of truth isn't visible.

`$maxPerKind` caps each category so one systemic mistake can't produce a
hundred-line card.

```php
function dbVerifyColumnSelfCheck(
    ?string $sqlPath = null,
    ?string $schemaPath = null,
    int $maxPerKind = 6
): array
```

Defined in [includes/db_verify_column_parse.php](/code/files/includes/db_verify_column_parse.php.md), line 110.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$sqlPath` | `?string` | `null` | path to freeitsm.sql; null means database/freeitsm.sql in this install |
| `$schemaPath` | `?string` | `null` | path to the schema file; null means includes/db_verify_schema.php |
| `$maxPerKind` | `int` | `6` | most problems listed per category before the rest are summarised |

**Returns:** `array`

## Calls

[dbVerifyNormaliseColumnType()](/code/functions/dbVerifyNormaliseColumnType.md), [dbVerifyParseColumnsFromSql()](/code/functions/dbVerifyParseColumnsFromSql.md)

## Called from

- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)

