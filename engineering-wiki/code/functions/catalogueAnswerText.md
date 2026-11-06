# catalogueAnswerText()

One stored answer as the words a person would use.

A `checkbox` is stored as 1 or 0, which read as "1" and "0" wherever an answer
is shown back - on the approval card and in the raised ticket. An approver
seeing "My budget holder has approved this spend: 1" has to know the storage
format to read their own inbox. `checkboxes` (plural) is a different field and
is stored as a JSON array of the chosen labels.

```php
function catalogueAnswerText(?string $raw, ?string $fieldType, array $field = []): string
```

Defined in [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), line 399.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `?string` |  | the stored form_submission_data.field_value; null reads as empty |
| `$fieldType` | `?string` |  | form_fields.field_type, e.g. 'checkbox', 'checkboxes', 'grid' |
| `$field` | `array` | `[]` | the form_fields row (label, config) - only used to label a 'grid' answer's columns |

**Returns:** `string`

## Called from

- [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md) - in [catalogueSubmissionAnswerMap()](/code/functions/catalogueSubmissionAnswerMap.md), [catalogueSubmissionBodyHtml()](/code/functions/catalogueSubmissionBodyHtml.md), [catalogueSubmissionAnswers()](/code/functions/catalogueSubmissionAnswers.md)

