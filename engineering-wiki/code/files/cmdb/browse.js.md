# cmdb/browse.js

> JavaScript - 386 lines

CMDB Browse page — class sidebar + object table. Click a class to filter; click a row to open the object detail page.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `escapeHtml(s)` | 14 | Escapes text for safe use in HTML. |
| `showInlineToast(msg, isError = false)` | 18 | Shows a toast - an error toast when isError, otherwise a success toast. |
| `postJson(url, body)` | 23 | Posts a JSON body and returns the parsed JSON reply. |
| `loadClasses()` | 37 | Loads the active CMDB classes from get_classes.php into the sidebar, or a pointer to settings when there are none. |
| `renderClassList()` | 57 | Draws the class sidebar with each class's object count, highlighting the selected one. |
| `selectClass(id)` | 69 | Selects a class - sets the title, enables New, clears the search and loads its objects. |
| `loadObjects(searchOverride = null)` | 82 | Loads the selected class's objects from get_objects.php, filtered by the search box (or an override), and draws the table. |
| `renderObjects()` | 100 | Draws the object table - name (with a Planned pill), parent, child count and last updated - or the empty state. |
| `formatDate(s)` | 137 | Formats a UTC timestamp in the analyst's zone and date format, or returns it unchanged if that fails. |
| `onSearchInput()` | 146 | Reloads the object list 200ms after the analyst stops typing in the search box. |
| `openObject(id)` | 152 | Opens an object's detail page. |
| `openNewObjectModal()` | 162 | Opens the New object modal for the selected class and adds inputs for any properties the class marks required. |
| `closeNewObjectModal()` | 185 | Closes the New object modal and forgets its required properties. |
| `renderRequiredFieldEditors(reqProps)` | 191 | Renders an input for each required property in the New object modal and wires the object-reference look-ups. |
| `renderFieldEditor(p)` | 208 | Returns the input HTML for one property by type - text, number, date, yes/no, drop-down, or an object-reference search box. |
| `wireRefAutocomplete(prop)` | 247 | Wires an object-reference input to search_objects.php (limited to the target class) with a keyboard-navigable result list; the picked id goes in a hidden input. |
| `renderResults()` | 257 | Draws the look-up results (or "no matches") with the highlighted row marked. |
| `pick(r)` | 279 | Fills the input with the chosen object's name and its id into the hidden input, then closes the list. |
| `collectRequiredFieldValues()` | 321 | Reads the required property inputs into {property_id, value} pairs typed by property, and lists the labels of any left empty. |
| `createObject()` | 348 | Creates the object through save_object.php with its required property values and Planned flag, then opens its detail page. |

## API endpoints it calls

- [api/cmdb/get_classes.php](/code/files/api/cmdb/get_classes.php.md)
- [api/cmdb/get_objects.php](/code/files/api/cmdb/get_objects.php.md)
- [api/cmdb/get_class_properties.php](/code/files/api/cmdb/get_class_properties.php.md)
- [api/cmdb/search_objects.php](/code/files/api/cmdb/search_objects.php.md)
- [api/cmdb/save_object.php](/code/files/api/cmdb/save_object.php.md)

## Loaded by

- [cmdb/index.php](/code/files/cmdb/index.php.md)

