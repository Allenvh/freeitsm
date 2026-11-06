# domainAlertDigest()

Subject and HTML for one recipient's digest.

```php
function domainAlertDigest(PDO $conn, string $name, array $items): array
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 279.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$name` | `string` |  | the recipient's display name; '' gives a plain "Hello," |
| `$items` | `array` |  | that recipient's due items from `domainAlertsDue()` (expiry, expired and ssl mixed) |

**Returns:** `array`

## Calls

[domainAlertWhen()](/code/functions/domainAlertWhen.md), [domainPublicLink()](/code/functions/domainPublicLink.md), [fmt_domain_date()](/code/functions/fmt_domain_date.md)

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md)

