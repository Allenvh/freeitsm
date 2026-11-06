# catalogueApprovalDecide()

Approve or reject a pending catalogue request.

```php
function catalogueApprovalDecide(
    PDO $conn,
    int $actorId,
    int $submissionId,
    string $decision,
    string $comment = ''
): array
```

Defined in [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), line 70.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actorId` | `int` |  | analysts.id of the person deciding - must be the request's approver unless an admin |
| `$submissionId` | `int` |  | form_submissions.id of the pending request |
| `$decision` | `string` |  | 'approved' or 'rejected' |
| `$comment` | `string` | `''` | the approver's optional note, trimmed; empty is stored as NULL |

**Returns:** array { decision, ticket_id, ticket_number }

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 77 | `catalogueApprovalDecide` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md) |  |
| 122 | `catalogueApprovalDecide` | **update** [form_submissions](/code/tables/form_submissions.md) (`approval_status`, `approval_decided_by_id`, `approval_decided_datetime`, `approval_comment`, `ticket_id`) |  |
| 129 | `catalogueApprovalDecide` | **update** [form_submissions](/code/tables/form_submissions.md) (`approval_status`, `approval_decided_by_id`, `approval_decided_datetime`, `approval_comment`) |  |
| 194 | `catalogueApprovalDecide` | **update** [form_submissions](/code/tables/form_submissions.md) (`ticket_id`) |  |

## Calls

[catalogueCreateTicketFromSubmission()](/code/functions/catalogueCreateTicketFromSubmission.md), [catalogueFormActionLists()](/code/functions/catalogueFormActionLists.md), [catalogueSubmissionAnswerMap()](/code/functions/catalogueSubmissionAnswerMap.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md)

## Called from

- [api/forms/decide_catalogue_approval.php](/code/files/api/forms/decide_catalogue_approval.php.md)

