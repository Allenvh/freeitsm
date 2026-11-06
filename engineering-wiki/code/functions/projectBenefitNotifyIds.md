# projectBenefitNotifyIds()

Who is reminded about a benefit's review (project_benefit_notify).

```php
function projectBenefitNotifyIds(PDO $conn, ?int $ownerId, ?int $pmId): array
```

Defined in [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), line 151.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ownerId` | `?int` |  | the benefit owner's analyst id; null when nobody owns it |
| `$pmId` | `?int` |  | the project manager's analyst id; null when there is none |

**Returns:** `array`

## Calls

[projectSetting()](/code/functions/projectSetting.md)

## Called from

- [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) - in [projectAlertsBenefits()](/code/functions/projectAlertsBenefits.md)

