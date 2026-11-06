# domainPublicLink()

Absolute link to a domain, for mail sent by a cron with no request host.

```php
function domainPublicLink(PDO $conn, int $id): string
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 267.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the domain's id |

**Returns:** `string`

## Calls

[publicBaseUrl()](/code/functions/publicBaseUrl.md)

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertDigest()](/code/functions/domainAlertDigest.md), [domainAlertOnChange()](/code/functions/domainAlertOnChange.md), [domainRenewalActionsRun()](/code/functions/domainRenewalActionsRun.md)

