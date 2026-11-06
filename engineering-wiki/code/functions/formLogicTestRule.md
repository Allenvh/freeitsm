# formLogicTestRule()

Evaluate one rule against the answers given so far.

```php
function formLogicTestRule(array $rule, array $valuesByFieldId): bool
```

Defined in [includes/form_logic.php](/code/files/includes/form_logic.php.md), line 67.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rule` | `array` |  | array{field:int,op:string,value:string} - one entry of visible_if.rules |
| `$valuesByFieldId` | `array` |  | field_id => stored answer; a missing field counts as blank |

**Returns:** `bool`

## Calls

[formLogicNormaliseValue()](/code/functions/formLogicNormaliseValue.md)

## Called from

- [includes/form_logic.php](/code/files/includes/form_logic.php.md) - in [formLogicVisibility()](/code/functions/formLogicVisibility.md)

