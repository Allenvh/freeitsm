# domainAlertClaim()

Claim an alert. true = ours to send; false = already sent (or claimed a moment ago).

```php
function domainAlertClaim(PDO $conn, int $domainId, string $kind, string $fingerprint): bool
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 52.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain the alert is about |
| `$kind` | `string` |  | audience prefix + kind, e.g. 'mail_expiry', 'evt_ssl', 'act_renew', 'mail_changed' |
| `$fingerprint` | `string` |  | what makes this alert unique within the kind - normally the date it is about |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 | `domainAlertClaim` | **insert** [domain_alerts_sent](/code/tables/domain_alerts_sent.md) (`domain_id`, `alert_kind`, `fingerprint`, `sent_datetime`) |  |

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md), [domainAlertOnChange()](/code/functions/domainAlertOnChange.md), [domainRenewalActionsRun()](/code/functions/domainRenewalActionsRun.md)

