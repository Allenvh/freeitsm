# domainStatusWording()

The title and opening comment, in plain words (stored data: what customers may read).

```php
function domainStatusWording(array $row, array $problems): array
```

Defined in [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md), line 155.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$row` | `array` |  | the domain row from `domainStatusRow()` (domain_name, display_name) |
| `$problems` | `array` |  | the non-empty list from `domainStatusProblems()`, each array{kind:string,fingerprint:string,date:string} |

**Returns:** `array`

## Called from

- [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) - in [domainStatusRaise()](/code/functions/domainStatusRaise.md)

