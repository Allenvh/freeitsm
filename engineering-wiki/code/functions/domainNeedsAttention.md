# domainNeedsAttention()

The reasons a domain belongs on the "needs attention" list — one place, so the register filter, the dashboard and Watchtower cannot disagree.

```php
function domainNeedsAttention(array $r): array
```

Defined in [includes/domains/read.php](/code/files/includes/domains/read.php.md), line 130.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a row already through `domainShapeRow()` (needs days_left and ssl_days_left) |

**Returns:** `array`

## Called from

- [includes/domains/read.php](/code/files/includes/domains/read.php.md) - in [domainShapeRow()](/code/functions/domainShapeRow.md)

