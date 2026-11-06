# fmt_domain_date()

'YYYY-MM-DD' → '20 November 2026'. Plain and not zone-shifted.

```php
function fmt_domain_date(?string $ymd): string
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 339.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ymd` | `?string` |  | a 'YYYY-MM-DD' date (anything after the first 10 characters is ignored); null or '' gives '' |

**Returns:** `string`

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertDigest()](/code/functions/domainAlertDigest.md), [domainRenewalActionsRun()](/code/functions/domainRenewalActionsRun.md)

