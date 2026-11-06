# assets/js/domains-table.js

> JavaScript - 91 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Domains Developer Guide](https://github.com/edmozley/freeitsm/wiki/Domains-Developer-Guide)

Domains — table view config for the shared data-table engine (assets/js/data-table.js). The engine does everything else: sorting, per-column filters, search, the column drawer, CSV export, saved views.

Inline edits post just the one changed field to api/domains/save.php — the
service updates only the keys it is sent, and records the change in the
domain's history exactly as the edit form would.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `yn(v)` | 18 | Shows a lock or DNSSEC flag as on, off, or blank when unknown. |
| `days(n)` | 20 | Shows a days-left number as text, blank when unknown. |
| `pick(list, v)` | 84 | Returns the name of the lookup item with the given id, so the edited cell shows its new label. |

## Loaded by

- [domains/table/index.php](/code/files/domains/table/index.php.md)

