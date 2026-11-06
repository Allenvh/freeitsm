# WorkflowTemplates

> Class in [workflow/includes/templates.php](/code/files/workflow/includes/templates.php.md), line 36

## Methods

| Method | What it does |
|---|---|
| [all()](#all) | The catalogue. Every `trigger_event` here is a real, wired trigger (checked against WorkflowEngine::availableTriggers()) and every action type is a real handler — a recipe that can't actually run would be worse than no recipe at all. |
| [get()](#get) | One template by key, or null. |
| [catalogue()](#catalogue) | The catalogue as the gallery UI wants it: no conditions/actions bodies, just what a human needs to choose — plus a plain-English step list so the card can show what the recipe will actually do. |
| [resolve()](#resolve) | Turn a template into a concrete workflow for THIS install: resolve every `$lookup` marker against the local lookup tables and blank out every `$configure` marker. |
| [resolveValue()](#resolvevalue) | Resolve one template value. Literals pass straight through; markers are expanded (or recorded as unresolved and blanked). |
| [humanList()](#humanlist) | ['A', 'B', 'C'] → "A, B or C" — for the unresolved-reason sentences. |

### all

*public static* - line 44

The catalogue. Every `trigger_event` here is a real, wired trigger (checked against WorkflowEngine::availableTriggers()) and every action type is a real handler — a recipe that can't actually run would be worse than no recipe at all.

```php
function all(): array
```

### get

*public static* - line 703

One template by key, or null.

```php
function get(string $key): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the template's key in `all()`, e.g. 'ticket_deleted_audit_trail' |

**Called from:** [api/workflow/create_from_template.php](/code/files/api/workflow/create_from_template.php.md)

### catalogue

*public static* - line 713

The catalogue as the gallery UI wants it: no conditions/actions bodies, just what a human needs to choose — plus a plain-English step list so the card can show what the recipe will actually do.

```php
function catalogue(): array
```

**Called from:** [api/workflow/templates.php](/code/files/api/workflow/templates.php.md)

### resolve

*public static* - line 754

Turn a template into a concrete workflow for THIS install: resolve every `$lookup` marker against the local lookup tables and blank out every `$configure` marker.

Returns:
  [
    'name', 'description', 'trigger_event', 'conditions', 'actions',
    'unresolved' => [ ['where' => 'Action 1 · Assign to', 'reason' => '...'], ... ],
  ]

Never throws on an unresolvable value — an install that has renamed
every priority should still get the workflow, with an honest list of
what it needs to fill in.

```php
function resolve(array $tpl): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tpl` | `array` |  | one template from `all()` (name, description, trigger_event, conditions, actions) |

**Called from:** [api/workflow/create_from_template.php](/code/files/api/workflow/create_from_template.php.md)

### resolveValue

*private static* - line 798

Resolve one template value. Literals pass straight through; markers are expanded (or recorded as unresolved and blanked).

```php
function resolveValue($val, string $where, array &$unresolved)
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$val` |  |  | a condition value or action arg: a literal, or a ['`$lookup`' => ..., 'names' => [...]] or ['`$configure`' => reason] marker |
| `$where` | `string` |  | where the value sits, in words, for the unresolved list, e.g. 'Action 1 (...) · Assign to' |
| `$unresolved` | `array` |  | by reference; a [where, reason] entry is added for each value left blank |

### humanList

*private static* - line 843

['A', 'B', 'C'] → "A, B or C" — for the unresolved-reason sentences.

```php
function humanList(array $names): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$names` | `array` |  | the names to list, quoted in the result |

