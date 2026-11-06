# catalogueRaiseTicketFromSubmissionId()

Raise a ticket from a submission id, loading the row the way the approval path does. The entry point for callers that hold an id rather than a row — the workflow engine's create_ticket action, and (next) a form's own action list.

```php
function catalogueRaiseTicketFromSubmissionId(
    PDO $conn,
    int $submissionId,
    array $overrides = []
): array
```

Defined in [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), line 371.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$submissionId` | `int` |  | form_submissions.id to raise the ticket from |
| `$overrides` | `array` | `[]` | ticket settings that win over the submission - see `catalogueCreateTicketFromSubmission()` |

**Returns:** array{ticket_id:int, ticket_number:string}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 373 | `catalogueRaiseTicketFromSubmissionId` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md) |  |

## Calls

[catalogueCreateTicketFromSubmission()](/code/functions/catalogueCreateTicketFromSubmission.md)

## Called from

- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_create_ticket`

