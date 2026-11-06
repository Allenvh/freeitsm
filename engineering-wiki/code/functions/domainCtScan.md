# domainCtScan()

Asks crt.sh for the unexpired certificates issued for a domain and stores any not seen before; the first scan stores them as acknowledged, later ones as new and fire domain.certificate_issued.

```php
function domainCtScan(PDO $conn, int $domainId, string $name): array
```

Defined in [includes/domains/watch.php](/code/files/includes/domains/watch.php.md), line 56.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain the certificates are stored against |
| `$name` | `string` |  | the stored domain name to ask crt.sh about |

**Returns:** array{ok:bool, new:int, total:int, error:?string, first_scan:bool}

**Writes:** domain_certificates - one row per crt.sh certificate id

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 65 | `domainCtScan` | read [domain_certificates](/code/tables/domain_certificates.md) |  |
| 70 | `domainCtScan` | **insert** [domain_certificates](/code/tables/domain_certificates.md) (`domain_id`, `crtsh_id`, `common_name`, `name_value`, `issuer`, `not_before`, `not_after`, `first_seen_datetime`, `acknowledged`) |  |

## Calls

[domainCtDate()](/code/functions/domainCtDate.md), [domainCtIssuer()](/code/functions/domainCtIssuer.md), [domainHttpGet()](/code/functions/domainHttpGet.md)

## Called from

- [api/domains/watch.php](/code/files/api/domains/watch.php.md)
- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainScheduledRun()](/code/functions/domainScheduledRun.md)

