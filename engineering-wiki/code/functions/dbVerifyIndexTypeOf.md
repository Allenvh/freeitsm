# dbVerifyIndexTypeOf()

Normalise an index type from either list format.

The generated list carried `true`/`false` before 2026-08 and carries
'unique'/'fulltext'/'key' after it. Reading both matters because the
consequence of guessing is not an error — `true ? 'UNIQUE KEY' : 'KEY'`
would happily read the string 'key' as truthy and build a UNIQUE index on a
column that has duplicates, which fails loudly, or worse the reverse.

```php
function dbVerifyIndexTypeOf($type): string
```

Defined in [includes/db_verify_index_parse.php](/code/files/includes/db_verify_index_parse.php.md), line 59.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` |  |  | a list entry's type: bool (old format, true = unique) or 'unique'/'fulltext'/'key'; anything else reads as 'key' |

**Returns:** `string`

## Called from

- [api/system/db_verify.php](/code/files/api/system/db_verify.php.md)
- [includes/db_verify_index_parse.php](/code/files/includes/db_verify_index_parse.php.md) - in [dbVerifyIndexListSelfCheck()](/code/functions/dbVerifyIndexListSelfCheck.md)

