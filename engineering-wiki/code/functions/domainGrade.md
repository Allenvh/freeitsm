# domainGrade()

Scores a domain's health findings out of 100 (each takes off its weight) and turns the score into a grade, A+ to F; an expired, redemption or NXDOMAIN finding is always F.

```php
function domainGrade(array $findings): array
```

Defined in [includes/domains/checks.php](/code/files/includes/domains/checks.php.md), line 281.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$findings` | `array` |  | the check findings, each with at least 'key' and 'weight' (points taken off) |

**Returns:** array{0:int,1:string}

## Called from

- [includes/domains/checks.php](/code/files/includes/domains/checks.php.md) - in [domainRunChecks()](/code/functions/domainRunChecks.md)

