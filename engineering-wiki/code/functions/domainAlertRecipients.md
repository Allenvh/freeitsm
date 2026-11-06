# domainAlertRecipients()

email => display name, for one due item.

```php
function domainAlertRecipients(array $a, array $s): array
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 230.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` | `array` |  | one due item from `domainAlertsDue()` (or a domain row with owner_email/owner_name added) |
| `$s` | `array` |  | every Domains setting, as `domainSettings()` returns them - reads the recipients mode and list |

**Returns:** `array`

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md), [domainAlertOnChange()](/code/functions/domainAlertOnChange.md)

