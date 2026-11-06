# projectCreateFromAction()

The workflow action create_project: a proposal from a form (or any rule). Answers fill what the action leaves blank - the same rule the form-to-ticket path follows: an override always wins, the submission fills the rest.

```php
function projectCreateFromAction(PDO $conn, array $a, array $payload): array
```

Defined in [includes/projects/intake.php](/code/files/includes/projects/intake.php.md), line 154.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$a` | `array` |  | the action's settings: name, summary, business_case, goal, estimated_cost, methodology, priority, owner_analyst_id ... |
| `$payload` | `array` |  | the workflow event payload - submission.id and submission.email are read |

**Returns:** array{project_id:int, code:string, name:string, approval:?string}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 161 | `projectCreateFromAction` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md) |  |
| 173 | `projectCreateFromAction` | read [users](/code/tables/users.md) |  |
| 205 | `projectCreateFromAction` | **update** [projects](/code/tables/projects.md) (`form_submission_id`, `proposed_by_name`, `proposed_by_email`, `created_by_id`) |  |
| 207 | `projectCreateFromAction` | read [projects](/code/tables/projects.md) |  |

## Calls

[catalogueSubmissionAnswers()](/code/functions/catalogueSubmissionAnswers.md), [projectCode()](/code/functions/projectCode.md), [projectMethodologies()](/code/functions/projectMethodologies.md), [projectPriorities()](/code/functions/projectPriorities.md)

## Called from

- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_create_project`

