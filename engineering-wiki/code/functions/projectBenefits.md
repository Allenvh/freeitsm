# projectBenefits()

One project's benefits with their measurements, state and progress - for its Benefits tab.

```php
function projectBenefits(PDO $conn, int $projectId): array
```

Defined in [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), line 75.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 | `projectBenefits` | read [project_benefits](/code/tables/project_benefits.md), read [analysts](/code/tables/analysts.md) |  |
| 85 | `projectBenefits` | read [project_benefit_measures](/code/tables/project_benefit_measures.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[projectBenefitsReady()](/code/functions/projectBenefitsReady.md), [projectBenefitState()](/code/functions/projectBenefitState.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)

