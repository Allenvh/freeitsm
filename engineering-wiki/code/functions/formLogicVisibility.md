# formLogicVisibility()

Work out which fields are visible, given the answers.

A field inside a hidden section is hidden too: a section owns every field after it
until the next section, so hiding the heading has to hide its contents or the rule
would look like it had done nothing.

```php
function formLogicVisibility(array $fields, array $valuesByFieldId): array
```

Defined in [includes/form_logic.php](/code/files/includes/form_logic.php.md), line 111.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fields` | `array` |  | rows with id, field_type, sort_order, config — IN sort_order |
| `$valuesByFieldId` | `array` |  | field_id => stored answer |

**Returns:** array<int,bool>       field id => visible

## Calls

[formLogicTestRule()](/code/functions/formLogicTestRule.md)

## Called from

- [includes/services/forms.php](/code/files/includes/services/forms.php.md) - in `FormsService::submitForm`

