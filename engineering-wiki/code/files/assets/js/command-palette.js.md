# assets/js/command-palette.js

> JavaScript - 434 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Command Palette Developer Guide](https://github.com/edmozley/freeitsm/wiki/Command-Palette-Developer-Guide)

Command palette (⌘/Ctrl-K).

A global launcher available on every analyst page. Opens with Cmd-K (Mac) or
Ctrl-K, lets you jump straight to any module you can access, run a couple of
quick actions, and search tickets / CMDB items / assets by name.

The page injects two globals before this loads (see renderWaffleMenuJS in
includes/waffle-menu.php):
  window.CP_BASE     — BASE_URL, prefixed to every navigation target.
  window.CP_MODULES  — [{ key, name, path, icon }] already filtered to the
                       modules this analyst may see, so the palette never
                       offers a destination the waffle launcher wouldn't.

Entity search goes to api/system/global_search.php, which applies the same
module + company scoping server-side.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `cp(key, english, params)` | 30 | Translate, or the English written right here. The waffle menu injects this file on every in-app page and not all of them export window.translations, so the fallback is the contract, not a nicety. |
| `typeLabel(type)` | 67 | Built lazily: the palette is constructed on first open, by which point window.translations is certainly in place. |
| `run()` | 95 | Flips between the dark and default themes through `setTheme()`, which saves the choice and reloads. |
| `run()` | 106 | Signs the analyst out. |
| `esc(s)` | 117 | HTML-escapes a value for building the palette's markup. |
| `build()` | 124 | Creates the palette overlay (search box, results list, key hints) on first open and wires its events. |
| `open()` | 155 | Shows the palette with an empty query (modules and actions) and focuses the search box. |
| `close()` | 165 | Hides the palette and cancels any search still waiting to run. |
| `isOpen()` | 173 | Whether the palette is currently showing. |
| `score(text, q)` | 177 | Substring match with a light prefix boost, so "tas" ranks "Tasks" above a module that merely contains the letters. |
| `matchedModules(q)` | 185 | The modules whose name matches the query, prefix matches first; every module when the query is empty. |
| `matchedCommands(q)` | 195 | The quick actions whose label or keywords match the query; all of them when the query is empty. |
| `render(serverResults)` | 203 | Render the palette body from the current query + optional server results. |
| `pluralType(type)` | 306 | The group heading for a search result type, e.g. 'Tickets' or 'Found inside tickets'. |
| `row(idx, iconSvg, title, sub, tag, extra)` | 337 | Builds the markup for one result row - icon, title, optional subtitle, extra button and type tag. |
| `setActive(ix)` | 350 | Makes the row at this index the highlighted one. |
| `paintActive()` | 356 | Highlights the active row and scrolls it into view. |
| `move(delta)` | 368 | Moves the highlight up or down by delta rows, wrapping round at either end. |
| `onKeydown(e)` | 375 | Arrow keys move the highlight, Enter opens the highlighted row and Escape closes the palette. |
| `onInput()` | 391 | Redraws the module and action matches at once, then runs the server search 180ms after typing stops (two characters or more). |
| `doSearch(q)` | 408 | Asks api/system/global_search.php for matches and redraws with them. |

## API endpoints it calls

- [api/system/global_search.php](/code/files/api/system/global_search.php.md)

