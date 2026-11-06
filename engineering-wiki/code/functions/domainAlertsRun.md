# domainAlertsRun()

The run: events, e-mail digests and renewal actions.

```php
function domainAlertsRun(PDO $conn, bool $dryRun = false): array
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 145.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$dryRun` | `bool` | `false` | count what WOULD be sent and send nothing — the settings screen shows this before anybody switches e-mail on. |

**Returns:** array{due:int, events:int, emails:int, recipients:int, failed:int, actions:int, would_email:array}

## Calls

[domainAlertAlreadySent()](/code/functions/domainAlertAlreadySent.md), [domainAlertClaim()](/code/functions/domainAlertClaim.md), [domainAlertDigest()](/code/functions/domainAlertDigest.md), [domainAlertPayload()](/code/functions/domainAlertPayload.md), [domainAlertRecipients()](/code/functions/domainAlertRecipients.md), [domainAlertRelease()](/code/functions/domainAlertRelease.md), [domainAlertsDue()](/code/functions/domainAlertsDue.md), [domainRenewalActionsRun()](/code/functions/domainRenewalActionsRun.md), [domainSettings()](/code/functions/domainSettings.md), [domainSettingWrite()](/code/functions/domainSettingWrite.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)

## Called from

- [api/domains/settings.php](/code/files/api/domains/settings.php.md)
- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainScheduledRun()](/code/functions/domainScheduledRun.md)

