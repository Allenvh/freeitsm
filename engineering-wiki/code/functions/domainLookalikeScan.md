# domainLookalikeScan()

Resolve the candidates and store the ones that exist.

```php
function domainLookalikeScan(
    PDO $conn,
    int $domainId,
    string $name,
    string $resolver = 'auto',
    float $budgetSeconds = 60
): array
```

Defined in [includes/domains/watch.php](/code/files/includes/domains/watch.php.md), line 198.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain the look-alikes are stored against |
| `$name` | `string` |  | the stored domain name to make variations of |
| `$resolver` | `string` | `'auto'` | auto \| system \| google \| cloudflare, as for `domainDns()` |
| `$budgetSeconds` | `float` | `60` | wall-clock seconds to spend before stopping with the rest unchecked |

**Returns:** array{checked:int, found:int, new:int, with_mx:int}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 204 | `domainLookalikeScan` | **insert** [domain_lookalikes](/code/tables/domain_lookalikes.md) (`domain_id`, `lookalike`, `technique`, `has_a`, `has_mx`, `first_seen_datetime`, `last_seen_datetime`) |  |
| 210 | `domainLookalikeScan` | read [domain_lookalikes](/code/tables/domain_lookalikes.md) |  |

## Calls

[domainDns()](/code/functions/domainDns.md), [domainLookalikeCandidates()](/code/functions/domainLookalikeCandidates.md)

## Called from

- [api/domains/watch.php](/code/files/api/domains/watch.php.md)
- [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md) - in [domainScheduledRun()](/code/functions/domainScheduledRun.md)

