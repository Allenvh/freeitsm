# catalogueApprovalsList()

The approver's inbox list.

```php
function catalogueApprovalsList(PDO $conn, int $analystId, string $filter = 'mine'): array
```

Defined in [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), line 566.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$filter` | `string` | `'mine'` | 'mine' (pending, assigned to me) \| 'all' (every pending) \| 'decided' (recently decided by me) |

**Returns:** array { items: [...], counts: { mine, all, decided } }

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 568 | `catalogueApprovalsList` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md), read [users](/code/tables/users.md), read [analysts](/code/tables/analysts.md), read [tickets](/code/tables/tickets.md) |  |
| 604 | `catalogueApprovalsList` | read [form_submissions](/code/tables/form_submissions.md) |  |
| 605 | `catalogueApprovalsList` | read [form_submissions](/code/tables/form_submissions.md) |  |
| 606 | `catalogueApprovalsList` | read [form_submissions](/code/tables/form_submissions.md) |  |

## Calls

[catalogueScalar()](/code/functions/catalogueScalar.md), [catalogueSubmissionAnswers()](/code/functions/catalogueSubmissionAnswers.md)

## Called from

- [api/forms/catalogue_approvals.php](/code/files/api/forms/catalogue_approvals.php.md)

