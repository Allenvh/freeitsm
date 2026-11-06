# domainChangeIsSerious()

Is this change one that should wake somebody up (vs. merely be recorded)?

```php
function domainChangeIsSerious(array $c): bool
```

Defined in [includes/domains/monitor.php](/code/files/includes/domains/monitor.php.md), line 104.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | one change from `domainBaselineDiff()` - array{field:string,old:string,new:string} |

**Returns:** `bool`

*Nothing calls this function by name.*

