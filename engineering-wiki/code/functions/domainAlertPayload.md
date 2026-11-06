# domainAlertPayload()

Shapes a due domain row into the 'domain' payload the domain.expiring and domain.ssl_expiring workflow events carry.

```php
function domainAlertPayload(array $a): array
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 251.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` | `array` |  | one due item from `domainAlertsDue()` - a domain row with its fields |

**Returns:** `array`

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md)

