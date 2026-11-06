# domainAlertWhen()

Says when in words - "expired 3 days ago", "today", "tomorrow", "in 30 days".

```php
function domainAlertWhen(int $days): string
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 326.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$days` | `int` |  | days until the date; negative for days since it passed |

**Returns:** `string`

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertDigest()](/code/functions/domainAlertDigest.md)

