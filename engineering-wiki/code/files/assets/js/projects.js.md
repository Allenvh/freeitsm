# assets/js/projects.js

> JavaScript - 526 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Projects Developer Guide](https://github.com/edmozley/freeitsm/wiki/Projects-Developer-Guide)

Projects module (3.2.0) - helpers every Projects page shares.

window.Prj: `api()`, `esc()`, `T()`, the renderers (icon, health ring, status pill,
date phrases), the create / edit project dialog, and `celebrate()` - so the
portfolio and the project page draw a project the same way.

Every URL is built from window.PRJ_API (set by the page from BASE_URL) -
never a root-relative "/api/...", which 404s on an install in a sub-directory.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 17 | Translates a key under projects.*, or returns the key when the i18n helper is not loaded. |
| `TC(k, p)` | 19 | Translates a key under common.*, or returns the key when the i18n helper is not loaded. |
| `esc(v)` | 22 | HTML-escapes a value for safe insertion into markup; null and undefined become an empty string. |
| `api(path, body)` | 33 | Calls a Projects API endpoint under window.PRJ_API - a GET with no body, a JSON POST with one. |
| `lookups()` | 50 | Fetches lookups.php once per page and caches the promise, so every caller shares one request. |
| `icon(key, size)` | 82 | Returns an inline SVG for an icon key at the given size (24px by default), falling back to the rocket. |
| `gradient(key)` | 90 | Returns the CSS linear-gradient for a colour key from the server palette, falling back to coral. |
| `setPalette(list)` | 95 | Replaces the colour palette with the server's list of {key, from, to} colours. |
| `ring(progress, health, size, onDark)` | 105 | A ring whose arc is the progress and whose colour is the health. The percentage sits inside. Finished projects draw a full, calm ring. |
| `statusPill(status)` | 119 | Returns the coloured pill markup for a project status, with its translated label. |
| `setPriorityLabels(l)` | 130 | Stores the priority words from Projects -> Settings, in low-to-critical order. |
| `priorityLabel(key)` | 132 | Returns the display word for a priority key - the configured label, else the translated default. |
| `priorityChip(key, always)` | 137 | Returns the priority chip markup, or nothing for medium (or no priority) unless always is set. |
| `healthBadge(health)` | 146 | Returns the health badge (dot and word) for green, amber or red, or nothing when there is no health. |
| `fmtDate(d)` | 156 | Formats a Y-m-d (or datetime) as a date for display, using the shared fmtNaiveDate when it is loaded. |
| `todayStr()` | 161 | Returns today's local date as Y-m-d. |
| `daysTo(d)` | 166 | Whole days from today to a Y-m-d (negative = in the past). |
| `targetPhrase(p)` | 172 | "Due in 12 days" / "3 days late" / "Finished 4 Mar" for a project. |
| `initials(name)` | 184 | Returns the upper-case initials of a name (first and last word), or "?" when there is no name. |
| `timeboxWord(kind, plural)` | 194 | Returns the lower-case translated word for a timebox kind (phase, stage or sprint; phase by default), singular or plural. |
| `toast(msg, type)` | 199 | Shows a toast through the shared showToast helper, if the page has one; success by default. |
| `openModal(id)` | 205 | Shows a modal by id and marks it visible to assistive technology. |
| `closeModal(id)` | 212 | Hides a modal by id and marks it hidden to assistive technology. |
| `paintBanner()` | 236 | Repaints the project dialog's preview banner from the chosen colour, icon and name, and tints the selected icon choice. |
| `pick(containerId, attr, value)` | 253 | Marks the one option in a radio-style card group whose attribute equals value as selected (class and aria-checked). |
| `openProjectForm(project, onSaved)` | 269 | Opens the create / edit project dialog, filling every field from the lookups and the project (or defaults for a new one). |
| `$(id)` | 283 | Shorthand for document.getElementById. |
| `templateMeta(t)` | 364 | "4 stages · 16 tasks · 12 weeks" - what a template brings. |
| `n(key, count)` | 366 | Returns the translated count phrase, using the _one form when the count is 1. |
| `chooseTemplate(key)` | 379 | Applies a project template in the dialog - its method, colour, icon and goal - or clears back to Blank when the key matches none. |
| `$(id)` | 382 | Shorthand for document.getElementById. |
| `wireForm()` | 409 | Wires the project dialog's click, input and save handlers once (guarded by data-wired on the modal). |
| `$(id)` | 414 | Shorthand for document.getElementById. |
| `showFormError(msg)` | 478 | Shows a message in the project dialog's error box. |
| `celebrate(fromEl)` | 489 | Confetti from an element, for the moments worth marking (a stage done, a project closed). Pure CSS transforms on a handful of dots, removed after ~1.2s. Respects prefers-reduced-motion by showing nothing but the toast. |

## Loaded by

- [projects/capacity.php](/code/files/projects/capacity.php.md)
- [projects/index.php](/code/files/projects/index.php.md)
- [projects/settings/index.php](/code/files/projects/settings/index.php.md)
- [projects/tutorial.php](/code/files/projects/tutorial.php.md)
- [projects/view.php](/code/files/projects/view.php.md)

