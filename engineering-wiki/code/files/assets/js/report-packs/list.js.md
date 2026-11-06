# assets/js/report-packs/list.js

> JavaScript - 316 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Report Packs Developer Guide](https://github.com/edmozley/freeitsm/wiki/Report-Packs-Developer-Guide)

Reporting -> Report Packs list (reporting/packs/index.php).

Everything a pack's name or description contains goes onto the page with
textContent, never innerHTML: a shared pack's name is somebody else's text.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 14 | Translates a key under reporting.packs.*, or returns the key when the i18n helper is not loaded. |
| `el(tag, cls, text)` | 25 | Creates an element with an optional class and text, set through textContent so names are never read as HTML. |
| `api(path, body)` | 39 | Calls a Report Packs API endpoint - a GET with no body, a JSON POST with one - and returns the reply. |
| `load()` | 52 | Loads the packs the analyst can see from list.php and renders the grid, or shows the error in it. |
| `render()` | 68 | Renders the pack cards that match the search box, the first-run empty state when there are no packs, or a no-match message. |
| `card(p)` | 91 | Builds one pack card - title, description, role, last change and the Open / Copy / Share / Delete actions the analyst's role allows. |
| `add(label, fn, cls)` | 120 | Adds an action link to the card that runs fn without also opening the pack. |
| `open(id)` | 132 | Opens a pack in the designer. |
| `openModal(id)` | 135 | ── Modals ────────────────────────────────────────────────────────── |
| `closeModal(id)` | 142 | Hides a modal by id. |
| `showError(id, msg)` | 148 | Shows a message in a dialog's error box, or hides the box when the message is empty. |
| `openNew(from)` | 155 | Opens the New pack dialog, or the Copy dialog prefilled from a pack when one is passed. |
| `create()` | 166 | Creates the pack (or the copy) through create.php and opens it in the designer; a name is required. |
| `askDelete(p)` | 184 | Opens the delete confirmation for a pack. |
| `confirmDelete()` | 193 | Deletes the pack through delete.php, removes it from the grid and closes the dialog; an error replaces the confirmation text. |
| `openShare(p)` | 207 | ── Sharing ───────────────────────────────────────────────────────── |
| `targetsFor(type)` | 226 | Returns the analysts, teams or departments that can be picked as share targets, as label / id pairs. |
| `fillShareTargets()` | 234 | Refills the share target suggestions for the chosen type and clears the input. |
| `addShare()` | 249 | Adds the typed analyst, team or department to the pack's share list, or updates its level if already there. |
| `key(s)` | 265 | Returns the identity of a share - type plus id, or the lower-cased name for a department. |
| `renderShares()` | 275 | Renders the share list with a view / edit level picker and a Remove link per entry. |
| `saveShares()` | 300 | Saves the whole share list through shares.php, then closes the dialog and refreshes the card's shared marker. |

## Loaded by

- [reporting/packs/index.php](/code/files/reporting/packs/index.php.md)

