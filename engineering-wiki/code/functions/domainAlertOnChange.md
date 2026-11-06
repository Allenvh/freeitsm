# domainAlertOnChange()

Change detection's e-mail. Called by DomainsService::runChecks() when something serious moved — separate from the daily digest because a possible hijack cannot wait for tomorrow's run.

```php
function domainAlertOnChange(PDO $conn, array $row, array $serious): void
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 354.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the domain row as loaded by DomainsService::runChecks() |
| `$serious` | `array` |  | the changes `domainChangeIsSerious()` kept, each array{field:string,old:string,new:string} |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 363 | `domainAlertOnChange` | read [analysts](/code/tables/analysts.md) |  |

## Calls

[domainAlertClaim()](/code/functions/domainAlertClaim.md), [domainAlertRecipients()](/code/functions/domainAlertRecipients.md), [domainAlertRelease()](/code/functions/domainAlertRelease.md), [domainPublicLink()](/code/functions/domainPublicLink.md), [domainSettings()](/code/functions/domainSettings.md), [domainWatchLabel()](/code/functions/domainWatchLabel.md), [ssSendSystemEmail()](/code/functions/ssSendSystemEmail.md)

## Called from

- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::runChecks`

