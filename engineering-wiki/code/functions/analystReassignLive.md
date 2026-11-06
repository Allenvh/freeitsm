# analystReassignLive()

Hand an analyst's open work to another analyst, or to nobody (null), through each module's own service. Per item, so one refusal (a project the acting analyst may not change, say) does not stop the rest. Returns what moved and what did not.

```php
function analystReassignLive(
    PDO $conn,
    ActorContext $ctx,
    int $fromId,
    ?int $toId,
    ?array $only = null
): array
```

Defined in [includes/analyst_lifecycle.php](/code/files/includes/analyst_lifecycle.php.md), line 494.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | the administrator doing it |
| `$fromId` | `int` |  | the analyst who is leaving |
| `$toId` | `?int` |  | the analyst taking the work; null leaves it unassigned |
| `$only` | `?array` | `null` | group keys to move (from `analystLiveGroups()`); null = all |

**Returns:** array{moved:array<string,int>,failed:array<int,string>}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 497 | `analystReassignLive` | read [analysts](/code/tables/analysts.md) |  |
| 534 | `analystReassignLive` | **update** [contracts](/code/tables/contracts.md) (`contract_owner_id`) |  |
| 539 | `analystReassignLive` | read [domains](/code/tables/domains.md) |  |

## Calls

[analystLiveGroups()](/code/functions/analystLiveGroups.md)

## Called from

- [api/tickets/analyst_leaving.php](/code/files/api/tickets/analyst_leaving.php.md)

