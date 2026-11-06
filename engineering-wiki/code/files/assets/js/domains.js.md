# assets/js/domains.js

> JavaScript - 170 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Domains Developer Guide](https://github.com/edmozley/freeitsm/wiki/Domains-Developer-Guide)

Domains module (#154) — helpers every Domains page shares.

window.Dom: `api()`, `esc()`, and the little renderers (grade badge, countdown
pill, status, locks, a check finding) so the register, the domain page, the
dashboard and the table view draw a domain the same way.

Every URL is built from window.DOM_API (set by the page from BASE_URL) —
never a root-relative "/api/…", which 404s on an install in a sub-directory.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `T(k, p)` | 17 | Translates a key from the 'domains' namespace, or returns the key when no translator is loaded. |
| `esc(v)` | 20 | HTML-escapes a value; null and undefined become an empty string. |
| `api(path, body)` | 30 | Calls a Domains endpoint under window.DOM_API - a GET, or a JSON POST when a body is given. |
| `fmtDate(d)` | 45 | Formats a stored date as a calendar date in the analyst's format, never shifted by time zone. |
| `grade(g, big)` | 51 | Draws the security grade badge (a dash when not graded); big for the domain page's hero. |
| `daysPill(days, warnAt)` | 58 | "in 12 days" / "expired 3 days ago", coloured by urgency. |
| `status(name, colour)` | 73 | Draws a domain status with its coloured dot, or "no status". |
| `lockIcon(state, icon, labelKey)` | 84 | Draws one lock icon as on, off or unknown, with a tooltip naming the lock and its state. |
| `locks(r)` | 91 | Transfer lock, registry lock, DNSSEC — three glances. |
| `purpose(p)` | 101 | Returns the translated name of a domain purpose. |
| `renewal(m)` | 103 | Returns the translated name of a renewal mode. |
| `tags(s)` | 106 | Draws a comma-separated tag list as tag chips. |
| `finding(f)` | 116 | One check finding. Its words come from lang/…/domains.php (check.<key>.title / .advice) with the finding's params filled in; the stored JSON carries no English, so a German analyst reads German. |
| `money(v, cur)` | 132 | Formats an amount in a currency (GBP when none is given); blank for no amount. |
| `tick()` | 143 | The no-cron fallback: ask the server to do a short scheduled batch. Fire and forget — the server decides whether an hour has passed, and the page never waits on it. |
| `openModal(id)` | 148 | Opens a dialog by id. |
| `closeModal(id)` | 150 | Closes a dialog by id. |
| `fillSelect(sel, list, opts)` | 153 | Fill a <select> from a list of {id,name}. |

## Loaded by

- [domains/accounts/index.php](/code/files/domains/accounts/index.php.md)
- [domains/dashboard/index.php](/code/files/domains/dashboard/index.php.md)
- [domains/index.php](/code/files/domains/index.php.md)
- [domains/settings/index.php](/code/files/domains/settings/index.php.md)
- [domains/table/index.php](/code/files/domains/table/index.php.md)
- [domains/view.php](/code/files/domains/view.php.md)

