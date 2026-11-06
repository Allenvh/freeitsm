# apiFormSubmissionsSelect()

The SELECT for form submissions, with the form's title and the submitter's name.

```php
function apiFormSubmissionsSelect(): string
```

Defined in [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md), line 346.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 347 | `apiFormSubmissionsSelect` | read [form_submissions](/code/tables/form_submissions.md), read [forms](/code/tables/forms.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) - in [apiFormSubmissionsList()](/code/functions/apiFormSubmissionsList.md)

