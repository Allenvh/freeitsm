# apiProblemSelect()

The SELECT for problems, with status, priority, analyst, creator and company names and how many tickets are linked.

```php
function apiProblemSelect(): string
```

Defined in [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md), line 40.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 41 | `apiProblemSelect` | read [problem_tickets](/code/tables/problem_tickets.md), read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md), read [problem_priorities](/code/tables/problem_priorities.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |

## Called from

- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiLoadProblem()](/code/functions/apiLoadProblem.md), [apiProblemsList()](/code/functions/apiProblemsList.md)

