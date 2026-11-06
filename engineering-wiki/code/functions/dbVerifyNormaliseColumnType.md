# dbVerifyNormaliseColumnType()

Reduce a column definition to a comparable shape.

The two files are written by different hands in different styles, so a naive
string compare would cry drift constantly and the guard would get ignored —
alarm fatigue is how a check like this dies. We therefore compare only what
genuinely changes behaviour: the base type (with any length/precision), and
whether it is NOT NULL.

Deliberately IGNORED: DEFAULT values, AUTO_INCREMENT, COMMENT, COLLATE,
ON UPDATE, and back-tick/whitespace/case differences. Those vary harmlessly
between the two files today, and a guard that fires on them is worse than no
guard at all.

```php
function dbVerifyNormaliseColumnType(string $def): string
```

Defined in [includes/db_verify_column_parse.php](/code/files/includes/db_verify_column_parse.php.md), line 83.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$def` | `string` |  | a column definition after the name, e.g. "varchar(100) NOT NULL DEFAULT ''" |

**Returns:** `string`

## Called from

- [includes/db_verify_column_parse.php](/code/files/includes/db_verify_column_parse.php.md) - in [dbVerifyParseColumnsFromSql()](/code/functions/dbVerifyParseColumnsFromSql.md), [dbVerifyColumnSelfCheck()](/code/functions/dbVerifyColumnSelfCheck.md)

