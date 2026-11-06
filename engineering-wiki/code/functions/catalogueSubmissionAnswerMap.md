# catalogueSubmissionAnswerMap()

A submission's answers as a label-keyed map, plus the first email answer.

The shape deliberately matches what `FormsService::submitForm()` puts on
`form.submitted`, so `{{submission.fields.Device type}}` means the same thing
whether a rule hangs off the submission or off the approval decision. A merge
code that resolved on one event and silently blanked on another would be worse
than not offering it at all.

⚠️ Values go through `catalogueAnswerText()`, so a checkbox reads "Yes"/"No"
rather than the stored 1/0. `form.submitted` still flattens raw — see the note
in the developer guide; unifying that changes what existing rules receive and
is a deliberate decision, not a tidy-up to slip in here.

⚠️ NOT derivable from `catalogueSubmissionAnswers()` below, and vice versa. That
one returns an ORDERED LIST because the approval card renders every row; this
returns a MAP keyed by label because a merge code addresses answers by name.
A map cannot represent two fields sharing a label — the second would silently
swallow the first — so the card must keep its list. Same data, two shapes,
both needed.

```php
function catalogueSubmissionAnswerMap(PDO $conn, int $submissionId): array
```

Defined in [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), line 500.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$submissionId` | `int` |  | form_submissions.id whose answers to read |

**Returns:** array{fields: array<string,string>, email: string}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 502 | `catalogueSubmissionAnswerMap` | read [form_submission_data](/code/tables/form_submission_data.md), read [form_fields](/code/tables/form_fields.md) |  |

## Calls

[catalogueAnswerText()](/code/functions/catalogueAnswerText.md)

## Called from

- [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) - in [catalogueApprovalDecide()](/code/functions/catalogueApprovalDecide.md)

