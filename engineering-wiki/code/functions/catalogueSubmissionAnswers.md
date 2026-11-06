# catalogueSubmissionAnswers()

label + value pairs for one submission (checkboxes flattened).

```php
function catalogueSubmissionAnswers(PDO $conn, int $submissionId): array
```

Defined in [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), line 617.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$submissionId` | `int` |  | form_submissions.id whose answers to list |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 619 | `catalogueSubmissionAnswers` | read [form_submission_data](/code/tables/form_submission_data.md), read [form_fields](/code/tables/form_fields.md) |  |

## Calls

[catalogueAnswerText()](/code/functions/catalogueAnswerText.md)

## Called from

- [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) - in [catalogueApprovalsList()](/code/functions/catalogueApprovalsList.md)
- [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) - in [projectCreateFromAction()](/code/functions/projectCreateFromAction.md)

