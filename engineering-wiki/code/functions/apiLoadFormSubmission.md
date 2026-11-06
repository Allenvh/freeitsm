# apiLoadFormSubmission()

Loads one submission that belongs to the given form, or answers 404 and stops.

```php
function apiLoadFormSubmission(PDO $conn, int $formId, int $submissionId): array
```

Defined in [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md), line 223.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$formId` | `int` |  | the forms id the submission must belong to |
| `$submissionId` | `int` |  | the form_submissions id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 225 | `apiLoadFormSubmission` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) - in [apiFormSubmissionsGet()](/code/functions/apiFormSubmissionsGet.md), [apiFormSubmissionsCreate()](/code/functions/apiFormSubmissionsCreate.md)

