# domainStatusProblems()

What is wrong with this domain right now, as far as Service Status is concerned. Each: kind ('expired' | 'cert'), fingerprint (the date), date.

```php
function domainStatusProblems(PDO $conn, array $row): array
```

Defined in [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md), line 64.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the domain row from `domainStatusRow()` (expiry_date, ssl_expiry_date, renewal_mode, alerts_enabled) |

**Returns:** `array`

## Calls

[domainSettings()](/code/functions/domainSettings.md)

## Called from

- [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md) - in [domainStatusState()](/code/functions/domainStatusState.md), [domainStatusRaise()](/code/functions/domainStatusRaise.md), [domainStatusRun()](/code/functions/domainStatusRun.md)

