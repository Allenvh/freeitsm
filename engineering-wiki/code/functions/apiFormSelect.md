# apiFormSelect()

The SELECT for forms, with creator and editor names and counts of child versions, fields and submissions - child_count 0 marks the current version.

```php
function apiFormSelect(): string
```

Defined in [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md), line 60.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 | `apiFormSelect` | read [forms](/code/tables/forms.md), read [form_fields](/code/tables/form_fields.md), read [form_submissions](/code/tables/form_submissions.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) - in [apiLoadForm()](/code/functions/apiLoadForm.md), [apiFormsList()](/code/functions/apiFormsList.md), [apiFormVersionsList()](/code/functions/apiFormVersionsList.md)

