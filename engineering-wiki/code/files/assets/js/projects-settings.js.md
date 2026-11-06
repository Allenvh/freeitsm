# assets/js/projects-settings.js

> JavaScript - 309 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 8 Connections and Templates](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-8-Connections-and-Templates)

Projects -> Settings (3.2.0).

Fills every [data-k] control from api/projects/settings.php, shows each
default under it, saves a tab at a time (the server checks that tab's
capability), runs the Roles list (add, edit, deactivate, delete, drag to
reorder) and the Templates list (show or hide a built-in; rename, switch off
or delete a saved one) over api/projects/templates.php.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `switchTab(tab)` | 19 | Shows a settings tab and puts it in the address bar (?tab=), so a reload stays on it. |
| `defaultLabel(key, value)` | 26 | A setting's default value in words, for the "Default is" line under its control. |
| `fill()` | 58 | Fills every setting control from the loaded values (drop-downs, risk scales, working days), writes each "Default is" line and redraws the roles list. |
| `save(tab)` | 89 | Saves the settings on one tab (the server checks that tab's capability) and refills the page. |
| `renderTemplates()` | 109 | Draws the built-in templates with a "Offered" tick box each, and the saved templates with an edit button. |
| `row(t, control)` | 113 | One template row - icon, name, what it sets up, who saved it, and its control. |
| `tplCall(body)` | 128 | Sends a templates action and redraws the list; errors go into the template dialog when it is open, else a toast. Returns true on success. |
| `wireTemplates()` | 142 | Loads the templates and the colour palette, draws them and wires the show/hide ticks and the edit dialog's save and delete. |
| `renderRoles()` | 179 | Draws the project roles list - name, description, how many use it, inactive flag and an edit button - with each row draggable. |
| `openRole(role)` | 193 | Opens the role dialog filled from role, or empty for a new one. |
| `renderRates()` | 207 | Each from a date: a new rate never re-prices time already logged. |
| `money(v)` | 212 | An amount in the install's currency for the viewer's locale. |
| `rateCall(body)` | 220 | Sends an hourly-rate action to settings.php and redraws the rates list; returns true on success. |
| `wireRates()` | 225 | Fills the rate picker (default or an analyst) and wires adding and deleting hourly rates. |
| `roleCall(body)` | 245 | Sends a roles action to settings.php and redraws the list; errors go into the role dialog when it is open, else a toast. Returns true on success. |

## Loaded by

- [projects/settings/index.php](/code/files/projects/settings/index.php.md)

