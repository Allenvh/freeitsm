# assets/js/domains-register.js

> JavaScript - 749 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Domains Developer Guide](https://github.com/edmozley/freeitsm/wiki/Domains-Developer-Guide)

Domains — the register page (domains/index.php).

The whole scoped register is loaded once (a few hundred rows is nothing) and
filtered, sorted and counted in the browser, so every sidebar click is
instant and the counts beside each view are always the counts of what you
would see. The server already removed anything outside the analyst's
companies — nothing here hides rows for security, only for convenience.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `init()` | 65 | Starts the register: applies ?view=, ?q= and ?account= from the address, loads the domains, opens Add when ?add=1 is set and fires the no-cron scheduled tick. |
| `load(withLookups)` | 80 | Reloads the whole register (with the lookups on the first call), drops selected ids that no longer exist, and redraws. |
| `fillLookups()` | 98 | Fills every filter and form drop-down from the lookups - the sidebar filters, Add, Add many, Import and Bulk edit - and shows the company pickers only when the analyst works across several companies. |
| `filtered()` | 130 | Returns the rows that pass the current view, account filter, search box and sidebar filters. |
| `v(id)` | 133 | Reads the value of a filter control by id. |
| `defaultCompanyId()` | 152 | Returns the id of the Default company, which a domain with no company belongs to for the company filter. |
| `render()` | 158 | Redraws the views, stats, table head and sorted body, the row count and the select-all box. |
| `renderViews()` | 189 | Draws the sidebar view buttons with their counts, flagging alert views that have anything in them. |
| `renderStats()` | 202 | Draws the clickable stat tiles (total, attention, expiring, expired, unlocked) and the annual cost when any domain has one. |
| `c(fn)` | 204 | Counts the rows that match a test. |
| `renderSelBar()` | 220 | Shows or hides the selection bar and updates its count. |
| `wire()` | 231 | Wires the register's controls once: search, filters, views, stats, sorting, row selection and clicks, the right-click menu, and the Add, Add many, Import and bulk buttons. |
| `openAdd()` | 286 | Clears and opens the Add domain dialog, focusing the name box. |
| `saveAdd()` | 296 | Saves one new domain (the server looks it up as it saves) and opens its page, warning when the lookup failed. |
| `openMany()` | 323 | Resets and opens the Add many dialog. |
| `saveMany()` | 336 | Sends the pasted list to bulk_add.php with the chosen defaults, then shows what was added and skipped and looks the new domains up in batches. |
| `showAddResult(boxId, d)` | 360 | "Added 42, skipped 3" then the lookups, in batches, with a progress bar. |
| `processIds(ids, label, holderId)` | 377 | Look up + check domains in batches of five, drawing progress. Anything not finished (the tab closed, the network dropped) is simply picked up by the scheduled run — a never-looked-up domain is first in its queue. |
| `draw()` | 390 | Redraws the progress line and bar. |
| `openImport()` | 430 | Resets the CSV import dialog to its first step and opens it. |
| `parseCsv(text)` | 440 | RFC 4180-ish: quoted fields, doubled quotes, commas or semicolons. |
| `readCsv()` | 466 | Reads the chosen CSV file, splits it into a header and rows, and moves on to column mapping. |
| `drawMapping()` | 483 | Draws a column picker for each import field, pre-choosing a column whose header matches a known name, and a preview of the first five rows. |
| `runImport()` | 503 | Builds one object per CSV row from the chosen mapping, sends them to bulk_add.php, then shows the result and looks the new domains up. Needs the domain column mapped. |
| `saveBulk()` | 532 | Applies the Bulk edit fields that were changed to every selected domain through bulk_update.php. |
| `put(id, key)` | 535 | Adds a field to the update when its control is not blank. |
| `bulkDelete()` | 550 | Deletes every selected domain after a confirm, then clears the selection and reloads. |
| `svg(k)` | 581 | Wraps one of the menu's icon paths in an SVG. |
| `ctxMenu()` | 584 | Returns the right-click menu element, creating it and its click handler the first time. |
| `item(action, icon, label, extra)` | 598 | Builds one right-click menu item for an action. |
| `sub(label, icon, opts)` | 603 | Builds a menu row that flies out a submenu of options. |
| `opt(field, value, label, current, swatch)` | 609 | Builds one submenu choice that sets a field to a value, ticked when it is the current value. |
| `openCtx(e, id)` | 619 | Opens the right-click menu for a domain at the pointer, kept on screen, with submenus flipped near the right or bottom edge. |
| `closeCtx()` | 651 | Hides the right-click menu and forgets which domain it was for. |
| `onCtxClick(e)` | 661 | Carries out the right-click menu choice: a status or owner change, open, edit, connections, visit, copy, assign to me, refresh, check or delete - through the same endpoints the domain's page uses. |
| `wireCtx()` | 718 | Wires the right-click menu: opening it on a row, closing it on an outside click, Escape, scroll or resize, and arrow-key movement in and out of submenus. |

## Loaded by

- [domains/index.php](/code/files/domains/index.php.md)

