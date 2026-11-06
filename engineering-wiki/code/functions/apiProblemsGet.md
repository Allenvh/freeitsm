# apiProblemsGet()

GET /problems/{id} — full detail incl. linked incidents + changes

```php
function apiProblemsGet(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md), line 213.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 218 | `apiProblemsGet` | read [problem_tickets](/code/tables/problem_tickets.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 241 | `apiProblemsGet` | read [change_relations](/code/tables/change_relations.md), read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md), [apiLoadProblem()](/code/functions/apiLoadProblem.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeProblem()](/code/functions/apiSerializeProblem.md)

*Nothing calls this function by name.*

