# formLogicNormaliseValue()

Split a stored answer into a scalar string + a list, so one operator works for both shapes.

```php
function formLogicNormaliseValue($raw): array
```

Defined in [includes/form_logic.php](/code/files/includes/form_logic.php.md), line 31.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` |  |  | one field's answer - a string, a JSON array string (checkboxes), a lookup's {"id","label"} JSON string, a PHP array, or null |

**Returns:** `array`

## Called from

- [includes/form_logic.php](/code/files/includes/form_logic.php.md) - in [formLogicTestRule()](/code/functions/formLogicTestRule.md)

