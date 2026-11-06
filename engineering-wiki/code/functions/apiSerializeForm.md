# apiSerializeForm()

Shapes a form row for the API - title, version (is_current marks the editable one), counts and people, plus its live fields unless `$withFields` is false.

```php
function apiSerializeForm(PDO $conn, array $r, bool $withFields = true): array
```

Defined in [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md), line 108.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$r` | `array` |  | the forms row as `apiFormSelect()` loads it, with child, field and submission counts and people names |
| `$withFields` | `bool` | `true` | false to leave out the 'fields' list (and its extra query), as the list endpoint does |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 130 | `apiSerializeForm` | read [form_fields](/code/tables/form_fields.md) |  |

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/forms.php](/code/files/api/v1/resources/forms.php.md) - in [apiFormsList()](/code/functions/apiFormsList.md), [apiFormsGet()](/code/functions/apiFormsGet.md), [apiFormsCreate()](/code/functions/apiFormsCreate.md), [apiFormsUpdate()](/code/functions/apiFormsUpdate.md), [apiFormVersionsList()](/code/functions/apiFormVersionsList.md), [apiFormVersionsCreate()](/code/functions/apiFormVersionsCreate.md)

