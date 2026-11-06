# domainAlertAlreadySent()

True when this alert (domain, kind, fingerprint) has already been claimed or sent.

```php
function domainAlertAlreadySent(PDO $conn, int $domainId, string $kind, string $fingerprint): bool
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 80.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain the alert is about |
| `$kind` | `string` |  | the kind to look for, e.g. 'mail_ssl' |
| `$fingerprint` | `string` |  | the fingerprint to look for |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 82 | `domainAlertAlreadySent` | read [domain_alerts_sent](/code/tables/domain_alerts_sent.md) |  |

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md)

