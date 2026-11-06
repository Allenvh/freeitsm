# assets/js/form-logic.js

> JavaScript - 477 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Form Sections and Conditional Logic Developer Guide](https://github.com/edmozley/freeitsm/wiki/Form-Sections-and-Conditional-Logic-Developer-Guide)

FormLogic — the one shared brain for the Forms module.

The three places a form gets drawn (the builder preview, forms/fill.php and the
portal's self-service/catalogue.php) each keep their own markup and CSS, because
they genuinely look different and merging them would change how existing forms
render. What they must NOT each keep their own copy of is the *thinking*: which
types exist, which carry options, and whether a field is currently visible.
That all lives here.

⚠️ Mirrors includes/form_logic.php, which is the source of truth — the server
re-evaluates visibility on submit, because a browser can simply not run this.
Change one, change the other; tests/forms-logic runs the same cases through both.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `lookupLabel(raw)` | 39 | A lookup answer is JSON: {"id":11,"label":"LT-001"}. The LABEL is what a condition compares against, because 'is LT-001' is what a form builder means — nobody writes a rule against a database id. |
| `configOf(field)` | 52 | A field's config as an object. |
| `lookupSource(field)` | 59 | The record type a lookup field searches (its config's lookup_source), or '' when none is set. |
| `fieldWidth(field)` | 73 | A field's width in twelfths. Absent means full, which is every field that predates the layout work. |
| `isAnswerable(type)` | 95 | Does this field type collect an answer? |
| `hasOptions(type)` | 97 | Whether a field type carries a list of options - drop-down, radio buttons or tickboxes. |
| `isMultiValue(type)` | 99 | Whether a field type's answer is a list of several values rather than one. |
| `noteStyle(field)` | 110 | A note's named style, defaulting when unset or unrecognised. |
| `labelPosition(field)` | 125 | Where a question's label sits. Mirrors FormsService::LABEL_POSITIONS. |
| `imageUrl(field, base)` | 144 | The URL an image block's picture is fetched from. |
| `imageMaxWidth(field)` | 150 | How wide an image may draw, as a percentage of its column. |
| `gridColumns(field)` | 173 | A table question's columns AS STORED — retired ones included. |
| `gridLiveColumns(field)` | 179 | The columns still being asked — what a filler draws. |
| `gridRows(raw)` | 189 | A table question's answer: always a list of rows, each a map of column id → value. |
| `noteBody(field)` | 200 | A note's optional longer text, beneath its title. |
| `dateMode(field)` | 206 | The mode of a datetime field, defaulting when unset. Mirrors FormsService::dateModeOf(). |
| `dateInputType(mode)` | 212 | The HTML input type a given mode needs. |
| `formatDateValue(raw)` | 226 | Render a stored value for READING (submissions table, CSV, detail drawer). |
| `parseOptions(raw)` | 231 | Options come back as a JSON string from the API but as a live array in the builder. |
| `normaliseValue(raw)` | 241 | Same as the PHP: a scalar string plus a list, so one operator handles both shapes. |
| `testRule(rule, valuesByFieldId)` | 275 | Tests one visibility condition against the current answers. |
| `visibility(fields, values)` | 316 | fields: array of {id, field_type, config} IN sort_order. values: { fieldId: answer }. Returns { fieldId: true\|false }. |
| `canReference(fields, fieldIndex, candidateIndex)` | 342 | True when a rule on `field` may point at `candidate` — earlier fields only, which is what stops cycles. |
| `attachLookups(root, searchUrl)` | 362 | Wire up every lookup box inside `root`. |
| `close()` | 375 | Hides and empties this lookup box's results list. |
| `choose(id, label)` | 380 | Stores the chosen record as {id, label} in the hidden input, shows its label and fires change so visibility rules re-run. |
| `search()` | 391 | Clears any earlier choice, then fetches matches for the typed text and lists them as buttons. |

## API endpoints it calls

- [api/forms/image.php](/code/files/api/forms/image.php.md)

## Loaded by

- [forms/collection.php](/code/files/forms/collection.php.md)
- [forms/fill.php](/code/files/forms/fill.php.md)
- [forms/submissions.php](/code/files/forms/submissions.php.md)

