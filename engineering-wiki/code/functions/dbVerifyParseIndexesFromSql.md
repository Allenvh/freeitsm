# dbVerifyParseIndexesFromSql()

Extract every named secondary index from freeitsm.sql text: [table, name, type, columns]. Skips PRIMARY KEY, FOREIGN KEY, CONSTRAINT.

`type` is 'unique', 'fulltext' or 'key'. It was a boolean (isUnique) until
2026-08; full-text search needs a third kind, and a two-valued flag had
nowhere to put it. `dbVerifyIndexTypeOf()` below reads either shape, so a
generated list from before the change still audits and applies correctly
rather than silently becoming the wrong kind of index.

```php
function dbVerifyParseIndexesFromSql(string $sql): array
```

Defined in [includes/db_verify_index_parse.php](/code/files/includes/db_verify_index_parse.php.md), line 30.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$sql` | `string` |  | the full text of database/freeitsm.sql |

**Returns:** `array`

## Called from

- [includes/db_verify_index_parse.php](/code/files/includes/db_verify_index_parse.php.md) - in [dbVerifyIndexListSelfCheck()](/code/functions/dbVerifyIndexListSelfCheck.md)

