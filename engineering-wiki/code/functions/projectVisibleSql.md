# projectVisibleSql()

" AND (...)" limiting `$alias` to the projects this analyst may see, with its arguments. Empty when nothing is hidden from them.

```php
function projectVisibleSql(
    PDO $conn,
    int $analystId,
    string $alias = 'p',
    bool $systemSeesAll = true
): array
```

Defined in [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), line 75.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'p'` | the alias the calling query gives the projects table |
| `$systemSeesAll` | `bool` | `true` | when there is no analyst (0, the system): true hides nothing, false hides members-only projects |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 80 | `projectVisibleSql` | read [project_members](/code/tables/project_members.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |

## Calls

[projectSeesAllPrivate()](/code/functions/projectSeesAllPrivate.md), [projectVisibilityReady()](/code/functions/projectVisibilityReady.md)

## Called from

- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsList()](/code/functions/apiProjectsList.md)
- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpProjectScopeSql()](/code/functions/mcpProjectScopeSql.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleProjects()](/code/functions/peopleProjects.md), [peopleContractorTasks()](/code/functions/peopleContractorTasks.md)
- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectListRows()](/code/functions/projectListRows.md)
- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectClause()](/code/functions/rpProjectClause.md), [rpProjectChoices()](/code/functions/rpProjectChoices.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotProjectScope()](/code/functions/warbotProjectScope.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

