# apiSerializeFormSubmission()

Shapes a submission for the API with every answer beside its field - checkbox answers decoded to lists, and a table's answer decoded to rows with its column definitions (retired columns included and marked).

```php
function apiSerializeFormSubmission(PDO $conn, array $r): array
```

Defined in [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md), line 159.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$r` | `array` |  | the form_submissions row as `apiLoadFormSubmission()` loads it, with form_title and submitted_by_name |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 163 | `apiSerializeFormSubmission` | read [form_submission_data](/code/tables/form_submission_data.md), read [form_fields](/code/tables/form_fields.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) - in [apiFormSubmissionsList()](/code/functions/apiFormSubmissionsList.md), [apiFormSubmissionsGet()](/code/functions/apiFormSubmissionsGet.md), [apiFormSubmissionsCreate()](/code/functions/apiFormSubmissionsCreate.md)

