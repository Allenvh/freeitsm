# domainLookupPace()

Space out requests to one registry. Called between lookups in a batch: the same RDAP/WHOIS host is not asked again within `$gapSeconds`.

```php
function domainLookupPace(PDO $conn, string $name, float $gapSeconds = 1.5): void
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 464.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$name` | `string` |  | the domain about to be looked up - its TLD decides which registry host is paced |
| `$gapSeconds` | `float` | `1.5` | least time between two requests to the same host, in seconds |

**Returns:** `void`

## Calls

[domainRdapBootstrap()](/code/functions/domainRdapBootstrap.md), [domainTld()](/code/functions/domainTld.md)

## Called from

- [api/domains/process.php](/code/files/api/domains/process.php.md)
- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainScheduledRun()](/code/functions/domainScheduledRun.md)

