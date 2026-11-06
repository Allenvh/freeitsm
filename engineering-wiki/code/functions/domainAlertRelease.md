# domainAlertRelease()

Gives back a claimed alert, so the next run tries it again - used when every e-mail for it failed.

```php
function domainAlertRelease(PDO $conn, int $domainId, string $kind, string $fingerprint): void
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain the alert is about |
| `$kind` | `string` |  | the kind it was claimed under, e.g. 'mail_expiry' |
| `$fingerprint` | `string` |  | the fingerprint it was claimed with |

**Returns:** `void`

**Writes:** domain_alerts_sent - removes the claim row

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 | `domainAlertRelease` | **delete** [domain_alerts_sent](/code/tables/domain_alerts_sent.md) |  |

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md), [domainAlertOnChange()](/code/functions/domainAlertOnChange.md), [domainRenewalActionsRun()](/code/functions/domainRenewalActionsRun.md)

