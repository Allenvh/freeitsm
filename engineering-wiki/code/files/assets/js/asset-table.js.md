# assets/js/asset-table.js

> JavaScript - 173 lines

FreeITSM Asset Management — table view config

Supplies the asset-specific pieces to the shared data-table engine
(assets/js/data-table.js): the COLUMNS catalogue and asset loading. The table
is read-only — clicking a row deep-links to the split-pane view for that
asset — and adds PDF export on top of the shared CSV. Everything else
(sort/filter/search/columns/preferences) is the shared engine.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `tt(k, p)` | 17 | Translates a key under asset-management, returning the key itself when translations are not loaded. |
| `staleDaysFromUrl()` | 101 | "Not seen in N days", arrived at from the Watchtower assets card (#97). |
| `applyStaleFilter(rows)` | 113 | When the page was opened with ?stale=N, keeps only assets last seen more than N days ago (never-seen assets excluded, matching the Watchtower count) and shows the banner with a link back to all assets. |

## API endpoints it calls

- [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md)

## Loaded by

- [asset-management/table.php](/code/files/asset-management/table.php.md)

