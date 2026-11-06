# cmdb/audit.js

> JavaScript - 106 lines

CMDB data-quality audit page.

Renders one card per check. Every card is shown even when it finds nothing —
a clean check is information ("references are sound"), and hiding it would
leave the reader unable to tell a passing check from one that never ran.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `esc(s)` | 22 | Escapes text for safe use in HTML. |
| `tr(key, params)` | 29 | Translates a key under cmdb.audit. |
| `loadAudit()` | 32 | Fetches the CMDB audit from AUDIT_API and renders it, or shows the error in the page. |
| `render(audit)` | 45 | Draws the audit - objects examined and total findings, then one card per check in CHECK_ORDER. |
| `card(c)` | 68 | Returns one check's card - clean, or its findings as links to the objects; the install-level "no impact edges" check links to settings instead. |

## API endpoints it calls

- [api/cmdb/get_audit.php](/code/files/api/cmdb/get_audit.php.md)

## Loaded by

- [cmdb/audit.php](/code/files/cmdb/audit.php.md)

