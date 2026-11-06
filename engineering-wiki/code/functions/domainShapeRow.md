# domainShapeRow()

Types, and the derived numbers every screen wants (days left, attention).

```php
function domainShapeRow(array $r): array
```

Defined in [includes/domains/read.php](/code/files/includes/domains/read.php.md), line 101.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | one row from `domainListSelect()`, straight from PDO (strings) |

**Returns:** `array`

## Calls

[domainNeedsAttention()](/code/functions/domainNeedsAttention.md)

## Called from

- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainDetail()](/code/functions/domainDetail.md)

