# projectBenefitsReady()

Has Database Verification created the tables?

```php
function projectBenefitsReady(PDO $conn): bool
```

Defined in [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `projectBenefitsReady` | read [project_benefits](/code/tables/project_benefits.md) |  |
| 38 | `projectBenefitsReady` | read [project_benefit_measures](/code/tables/project_benefit_measures.md) |  |

## Called from

- [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) - in [projectBenefits()](/code/functions/projectBenefits.md), [projectBenefitStats()](/code/functions/projectBenefitStats.md), [projectAlertsBenefits()](/code/functions/projectAlertsBenefits.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateCapture()](/code/functions/projectTemplateCapture.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::createFromTemplate`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveBenefit`

