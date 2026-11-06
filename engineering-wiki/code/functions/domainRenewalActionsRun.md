# domainRenewalActionsRun()

Raise a task or a ticket for each domain entering the renewal window. Once per expiry date (a renewed domain re-arms). Ownerless domains get an unassigned task — renewing is still somebody's job even when nobody has been named.

```php
function domainRenewalActionsRun(PDO $conn, array $s): int
```

Defined in [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md), line 407.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$s` | `array` |  | every Domains setting, as `domainSettings()` returns them - reads the renewal action and window |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 411 | `domainRenewalActionsRun` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[domainAlertClaim()](/code/functions/domainAlertClaim.md), [domainAlertRelease()](/code/functions/domainAlertRelease.md), [domainPublicLink()](/code/functions/domainPublicLink.md), [fmt_domain_date()](/code/functions/fmt_domain_date.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [includes/domains/alerts.php](/code/files/includes/domains/alerts.php.md) - in [domainAlertsRun()](/code/functions/domainAlertsRun.md)

