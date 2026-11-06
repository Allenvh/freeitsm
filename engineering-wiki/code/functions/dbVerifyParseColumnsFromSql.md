# dbVerifyParseColumnsFromSql()

Extract the declared COLUMNS of every table in freeitsm.sql: ['table' => ['column' => 'NORMALISED TYPE', ...], ...]

Skips key/constraint lines (PRIMARY KEY, KEY, UNIQUE KEY, INDEX, CONSTRAINT,
FOREIGN KEY) and `--` comments, which is everything inside a CREATE TABLE
that isn't a column.

```php
function dbVerifyParseColumnsFromSql(string $sql): array
```

Defined in [includes/db_verify_column_parse.php](/code/files/includes/db_verify_column_parse.php.md), line 45.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$sql` | `string` |  | the full text of database/freeitsm.sql |

**Returns:** `array`

## Calls

[dbVerifyNormaliseColumnType()](/code/functions/dbVerifyNormaliseColumnType.md)

## Called from

- [includes/db_verify_column_parse.php](/code/files/includes/db_verify_column_parse.php.md) - in [dbVerifyColumnSelfCheck()](/code/functions/dbVerifyColumnSelfCheck.md)

