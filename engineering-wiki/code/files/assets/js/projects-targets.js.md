# assets/js/projects-targets.js

> JavaScript - 237 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Internals 5 Health and Charts](https://github.com/edmozley/freeitsm/wiki/Projects-Internals-5-Health-and-Charts)

Projects - asset targets on a project's Overview (3.2.0): live progress measured from Assets ("12 of 44 Latitude 5430s retired"), each with a burn-up line, plus the dialog that sets one up and the "what's left" list.

projects-view.js draws the Overview with an empty #pvTargets and then calls
PrjTargets.render({data, projectId, refresh}). Every rule - the whitelisted
fields, the company, Assets access - is server-side in
includes/projects/targets.php; this file only draws and asks.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `canChange()` | 20 | True unless the server says the viewer may not change this project. |
| `canAssets()` | 22 | True when the viewer can open Assets, which setting up a target and listing its assets need. |
| `get(q)` | 24 | A GET to tools.php for the current project with the given query string. |
| `call(body)` | 26 | Posts a targets action to tools.php for the current project. |
| `$(id)` | 28 | Shorthand for document.getElementById. |
| `scopePhrase(t)` | 33 | Which assets a target counts, in words - those linked to the project, or the asset type and field filter. |
| `rulePhrase(t)` | 41 | What counts as done for a target, in words - "Status is Retired". |
| `spark(t)` | 48 | The target's small burn-up line as an inline SVG, or a "line soon" note until there are two points. |
| `card(t)` | 62 | One target's card - name, health dot, edit button, done of total with the percentage, the rule, progress bar, burn-up line, scope, due date and the "show what is left" link. |
| `render()` | 83 | Draws the Overview's asset targets panel - a card per target, or an inviting empty state for those who may add one; hidden otherwise. |
| `options()` | 102 | The target dialog's choices (asset types, fields, operators, statuses, locations), fetched once. |
| `fillOps(field, sel)` | 107 | Fills the operator list for the chosen done-field and shows a drop-down of statuses or locations, or a text box, for the value. |
| `setScope(s)` | 116 | Switches the dialog between counting linked assets and counting a filter, and refreshes the preview. |
| `rule()` | 124 | The target as typed in the dialog, ready to send. |
| `preview()` | 135 | After a 250ms pause, asks the server how many assets the dialog's rule matches and how many are done, and shows it. |
| `open(t)` | 148 | Opens the target dialog filled from t, or empty for a new one, loading its choices first. |
| `save()` | 172 | Saves the target dialog through tools.php and redraws the project. |
| `openList(t, show)` | 182 | Opens the list of a target's assets still to do (or done) - up to 200, with a note when there are more. |
| `find(id)` | 208 | The target with this id. |
| `wire()` | 211 | Wires the targets panel's add, edit and list buttons, the dialog's fields (which refresh the preview), save and delete, and the list's left / done switch, once. |

## Loaded by

- [projects/view.php](/code/files/projects/view.php.md)

