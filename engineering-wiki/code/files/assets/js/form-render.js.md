# assets/js/form-render.js

> JavaScript - 177 lines

FormRender — the one walk over a form's fields.

🔑 WHY THIS FILE EXISTS. A form is drawn in three places, each with its own
markup and its own per-type switch:

  forms/fill.php              the analyst filler   (.form-field, error divs)
  self-service/catalogue.php  the portal           (.cat-field, id="fN")
  forms/edit/index.php        the builder preview  (.preview-field, disabled)

⚠️ THEIR MARKUP IS NOT MERGED, AND MUST NOT BE. `form-logic.js` already makes
that argument and it is right: the three genuinely look different, and one
function emitting all three would change how every existing form renders for
no benefit. What they must not each own is the *walk* — the order fields come
in, the width each one gets, and what happens to a type nobody taught them.

🔴 THE FAILURE THIS EXISTS TO STOP. Before this file, an unhandled field type
did three different silent things:

  filler   no `default:` at all      -> the question VANISHED from the form
  portal   `default:` rendered text  -> a text box pretending to be the control,
                                        accepting and storing a wrong answer
  preview  `default: return ''`      -> the question VANISHED from the preview

All three are silent, and the portal's is the worst: it takes an answer. A
surface now returns null for a type it cannot draw and this file renders a
visible, non-submittable notice and logs to the console. Loud, in one place.

Depends on: FormLogic (widths), window.t (i18n).

## Functions

| Function | Line | What it does |
|---|--:|---|
| `render(fields, surface, layout)` | 50 | Walk a form and build its markup. |
| `poolCells(fields)` | 92 | The pool in its own order, each field at its own width. |
| `cellsOf(layout, fields)` | 111 | A layout, flattened to the stream of cells the surfaces draw. |
| `unknown(field, ctx, surfaceName)` | 156 | What a surface shows for a field type it cannot draw. |

## Loaded by

- [forms/fill.php](/code/files/forms/fill.php.md)

