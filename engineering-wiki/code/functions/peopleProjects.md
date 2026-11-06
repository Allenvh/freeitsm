# peopleProjects()

Projects (3.2.0): for a person, the projects they are a member of (People tab of a project) with their role and their RACI duties ("Accountable for Phones working on day one"); for a company, its projects. Live ones first. Limited to the companies the analyst can access, like Projects itself.

```php
function peopleProjects(PDO $conn, int $analystId, array $who): array
```

Defined in [includes/people.php](/code/files/includes/people.php.md), line 480.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$who` | `array` |  | ['user' => users id] for projects they are a member of, or ['company' => tenant id] |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 492 | `peopleProjects` | read [project_members](/code/tables/project_members.md), read [projects](/code/tables/projects.md), read [project_roles](/code/tables/project_roles.md), read [analysts](/code/tables/analysts.md) |  |
| 500 | `peopleProjects` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |
| 513 | `peopleProjects` | read [project_raci](/code/tables/project_raci.md), read [project_members](/code/tables/project_members.md), read [project_items](/code/tables/project_items.md) |  |

## Calls

[entityLink()](/code/functions/entityLink.md), [peopleCompanyMatch()](/code/functions/peopleCompanyMatch.md), [peopleScope()](/code/functions/peopleScope.md), [projectColourHex()](/code/functions/projectColourHex.md), [projectDecorate()](/code/functions/projectDecorate.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectsPhase2Ready()](/code/functions/projectsPhase2Ready.md), [projectsSchemaReady()](/code/functions/projectsSchemaReady.md), [projectTaskStats()](/code/functions/projectTaskStats.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md)

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [personDetail()](/code/functions/personDetail.md), [companyDetail()](/code/functions/companyDetail.md)

