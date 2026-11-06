# cmdb/options-editor.js

> JavaScript - 243 lines

CMDB shared dropdown-options editor.

Used by both the Settings page property modal and the floating
Edit-Property modal on the object detail page. Renders one row per option:
value text input + colour swatch + ⬆⬇ reorder + × delete, plus an "Add"
button below the list.

API:
  renderOptionsEditor(containerId, initialOptions)
    containerId — element to render into
    initialOptions — array of either:
        strings (legacy: ["Low", "Medium"])
      or
        {value, colour} objects (new: [{value:"Low", colour:"#22c55e"}, ...])

  collectOptionsFromEditor(containerId) -> [{value, colour}]
    Reads the current rows back as a normalised array of objects with the
    hex colour or null. Empty values are dropped.

No external dependencies.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `escapeHtmlAttr(s)` | 26 | Escapes text for safe use in HTML and attributes. |
| `normalizeInitial(opts)` | 31 | Turns the stored options (plain strings or {value, colour}) into {value, colour} objects, colour '' when unset. |
| `renderRow(value, colour, index)` | 43 | Returns one option row - value box, colour swatch, clear-colour, up, down and delete buttons. |
| `injectStylesOnce()` | 61 | Adds the editor's stylesheet to the page the first time an editor is rendered. |
| `wireRowHandlers(container)` | 152 | Wires every row's delete, move up, move down, colour pick and clear-colour buttons. |
| `renderOptionsEditor(containerId, initialOptions)` | 201 | Renders the options editor into a container from the stored options, with an Add button that appends an empty row. |
| `collectOptionsFromEditor(containerId)` | 228 | Reads the rows back in their current order as [{value, colour}], dropping empty values; colour is '' when none was picked. |

## Loaded by

- [cmdb/object.php](/code/files/cmdb/object.php.md)
- [cmdb/settings/index.php](/code/files/cmdb/settings/index.php.md)

