<?php
/**
 * Conditional visibility for form fields — the SERVER-SIDE source of truth.
 *
 * A field's `config` JSON may carry a visibility rule:
 *
 *   {"visible_if": {"match": "all|any", "rules": [
 *       {"field": <form_fields.id>, "op": "equals", "value": "Yes"}
 *   ]}}
 *
 * No rule (the NULL config every pre-existing field has) means always visible, so
 * an upgraded install renders exactly as it did before.
 *
 * ⚠️ A rule may only reference an EARLIER field (lower sort_order). That is enforced
 * when the form is saved (FormsService::validateFields), and it is what makes cycles
 * structurally impossible rather than something we have to detect at evaluation time.
 * Evaluating in sort_order therefore always has the answers it depends on.
 *
 * `assets/js/form-logic.js` mirrors this for live show/hide while someone types. The
 * two must agree, so the operator semantics below are the contract — tests/forms-logic
 * exercises both against the same cases. The browser copy is a convenience; THIS one
 * decides whether an answer was really required, because a client can simply not run.
 */

/**
 * Split a stored answer into a scalar string + a list, so one operator works for both shapes.
 *
 * @param mixed $raw  one field's answer - a string, a JSON array string (checkboxes), a lookup's
 *                    {"id","label"} JSON string, a PHP array, or null
 */
function formLogicNormaliseValue($raw): array
{
    $scalar = is_array($raw) ? '' : trim((string)($raw ?? ''));
    $list   = [];

    if (is_array($raw)) {
        $list   = array_map('strval', $raw);
        $scalar = implode(', ', $list);
    } elseif ($scalar !== '' && ($scalar[0] === '[')) {
        // checkboxes answers are stored as a JSON array string.
        $decoded = json_decode($scalar, true);
        if (is_array($decoded)) {
            $list   = array_map('strval', $decoded);
            $scalar = implode(', ', $list);
        }
    } elseif ($scalar !== '' && $scalar[0] === '{') {
        // A lookup answer is {"id":11,"label":"LT-001"}. A rule compares against
        // the LABEL — "is LT-001" is what a form builder means; nobody writes a
        // condition against a database id. Mirrors lookupLabel() in form-logic.js.
        $decoded = json_decode($scalar, true);
        if (is_array($decoded) && isset($decoded['label'])) {
            $scalar = trim((string)$decoded['label']);
        }
    }
    if (!$list && $scalar !== '') {
        $list = [$scalar];
    }
    return ['scalar' => $scalar, 'list' => $list];
}

/**
 * Evaluate one rule against the answers given so far.
 *
 * @param array $rule             array{field:int,op:string,value:string} - one entry of visible_if.rules
 * @param array $valuesByFieldId  field_id => stored answer; a missing field counts as blank
 */
function formLogicTestRule(array $rule, array $valuesByFieldId): bool
{
    $refId = (int)($rule['field'] ?? 0);
    $op    = (string)($rule['op'] ?? 'equals');
    $want  = trim((string)($rule['value'] ?? ''));

    $norm   = formLogicNormaliseValue($valuesByFieldId[$refId] ?? '');
    $scalar = $norm['scalar'];
    $list   = $norm['list'];

    switch ($op) {
        case 'is_empty':      return $scalar === '' || $scalar === '0';
        case 'is_not_empty':  return $scalar !== '' && $scalar !== '0';
        case 'equals':        return in_array($want, $list, true) || $scalar === $want;
        case 'not_equals':    return !(in_array($want, $list, true) || $scalar === $want);
        case 'contains':
            if (in_array($want, $list, true)) return true;
            return $want !== '' && mb_stripos($scalar, $want) !== false;
        case 'greater_than':  return is_numeric($scalar) && is_numeric($want) && (float)$scalar >  (float)$want;
        case 'less_than':     return is_numeric($scalar) && is_numeric($want) && (float)$scalar <  (float)$want;

        // Date-shaped comparison. A plain string compare is CORRECT here rather than
        // lazy: every stored date/time value is ISO-8601 (YYYY-MM-DD, HH:MM,
        // YYYY-MM-DDTHH:MM), and ISO-8601 is designed so lexical order IS chronological
        // order. Parsing to a timestamp would drag in a timezone these naive values
        // deliberately do not have.
        case 'is_after':      return $scalar !== '' && $want !== '' && strcmp($scalar, $want) > 0;
        case 'is_before':     return $scalar !== '' && $want !== '' && strcmp($scalar, $want) < 0;
    }
    // An operator we don't recognise must not silently hide a question.
    return true;
}

/**
 * Work out which fields are visible, given the answers.
 *
 * @param array $fields          rows with id, field_type, sort_order, config — IN sort_order
 * @param array $valuesByFieldId field_id => stored answer
 * @return array<int,bool>       field id => visible
 *
 * A field inside a hidden section is hidden too: a section owns every field after it
 * until the next section, so hiding the heading has to hide its contents or the rule
 * would look like it had done nothing.
 */
function formLogicVisibility(array $fields, array $valuesByFieldId): array
{
    $visible          = [];
    $currentSectionOk = true;

    foreach ($fields as $f) {
        $id  = (int)$f['id'];
        $cfg = $f['config'] ?? null;
        if (is_string($cfg)) {
            $cfg = json_decode($cfg, true);
        }

        $own = true;
        $vif = is_array($cfg) ? ($cfg['visible_if'] ?? null) : null;
        if (is_array($vif) && !empty($vif['rules']) && is_array($vif['rules'])) {
            $match   = (($vif['match'] ?? 'all') === 'any') ? 'any' : 'all';
            $results = [];
            foreach ($vif['rules'] as $rule) {
                if (is_array($rule)) {
                    $results[] = formLogicTestRule($rule, $valuesByFieldId);
                }
            }
            if ($results) {
                $own = ($match === 'any') ? in_array(true, $results, true) : !in_array(false, $results, true);
            }
        }

        if (($f['field_type'] ?? '') === 'section') {
            // The heading opens a new section; its own rule decides the whole block.
            $currentSectionOk = $own;
            $visible[$id]     = $own;
            continue;
        }

        $visible[$id] = $own && $currentSectionOk;
    }

    return $visible;
}
