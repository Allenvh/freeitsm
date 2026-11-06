# includes/form_logic.php

> PHP - 150 lines

Conditional visibility for form fields — the SERVER-SIDE source of truth.

A field's `config` JSON may carry a visibility rule:

  {"visible_if": {"match": "all|any", "rules": [
      {"field": <form_fields.id>, "op": "equals", "value": "Yes"}
  ]}}

No rule (the NULL config every pre-existing field has) means always visible, so
an upgraded install renders exactly as it did before.

⚠️ A rule may only reference an EARLIER field (lower sort_order). That is enforced
when the form is saved (FormsService::validateFields), and it is what makes cycles
structurally impossible rather than something we have to detect at evaluation time.
Evaluating in sort_order therefore always has the answers it depends on.

`assets/js/form-logic.js` mirrors this for live show/hide while someone types. The
two must agree, so the operator semantics below are the contract — tests/forms-logic
exercises both against the same cases. The browser copy is a convenience; THIS one
decides whether an answer was really required, because a client can simply not run.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [formLogicNormaliseValue()](/code/functions/formLogicNormaliseValue.md) | 31 | Split a stored answer into a scalar string + a list, so one operator works for both shapes. |
| [formLogicTestRule()](/code/functions/formLogicTestRule.md) | 67 | Evaluate one rule against the answers given so far. |
| [formLogicVisibility()](/code/functions/formLogicVisibility.md) | 111 | Work out which fields are visible, given the answers. |

## How it hangs together

**Included by:** [includes/services/forms.php](/code/files/includes/services/forms.php.md)

