# catalogueSubmissionBodyHtml()

The submitted answers as a safe (fully-escaped) HTML summary for the ticket body.

```php
function catalogueSubmissionBodyHtml(PDO $conn, int $submissionId, string $formTitle): string
```

Defined in [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), line 529.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$submissionId` | `int` |  | form_submissions.id whose answers to render |
| `$formTitle` | `string` |  | the form's name for the opening line; escaped here, so raw text is fine |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 531 | `catalogueSubmissionBodyHtml` | read [form_submission_data](/code/tables/form_submission_data.md), read [form_fields](/code/tables/form_fields.md) |  |

## Calls

[catalogueAnswerText()](/code/functions/catalogueAnswerText.md)

## Called from

- [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) - in [catalogueCreateTicketFromSubmission()](/code/functions/catalogueCreateTicketFromSubmission.md)

