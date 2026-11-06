# assets/js/subscribe.js

> JavaScript - 200 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Ticket Calendar Sync Developer Guide](https://github.com/edmozley/freeitsm/wiki/Ticket-Calendar-Sync-Developer-Guide)

Behaviour for the shared "add this calendar to your phone" dialogue.

Markup comes from includes/subscribe_modal.php; both the Calendar module and
Preferences (an analyst's own scheduled work) mount one. Keyed by the same id
prefix the markup was rendered with, so a page could carry more than one.

Each mount names the endpoint that mints its feed URL. That endpoint returns
{ scheme, host, path, url, insecure } — the components as well as the finished
URL, so the host can be swapped in the browser without another round trip.

⚠️ Needs qrcode.min.js. Without it everything still works; only the QR is
missing, which is why its failure is swallowed rather than reported.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `$(id)` | 25 | Shorthand for document.getElementById. |
| `t(key, fallback)` | 28 | Translates a key, or returns the English fallback when i18n is not loaded or has no string for it. |
| `refresh(id)` | 37 | Rebuild the URL + QR from whatever host is currently typed. |
| `apply(id, d)` | 67 | Stores a mount's feed URL parts, fills the host box (the LAN address instead of localhost, so a phone can reach it), shows the insecure warning when needed and redraws. |
| `oninput()` | 78 | Rebuilds the link and QR code as the host is edited. |
| `normalise(d)` | 92 | Endpoints differ in shape: the calendar's returns scheme/host/path, the work-calendar one returns a finished url. Accept either, so a caller is not forced to change its API to use this. |
| `mount(id, endpoint, opts)` | 107 | Register a dialogue. onReset is called after a successful rotation. |
| `open(id)` | 112 | Opens a subscribe dialogue, fetching its feed URL from the endpoint the first time. |
| `show()` | 117 | Shows the dialogue. |
| `close(id)` | 133 | Closes a subscribe dialogue. |
| `copy(id)` | 139 | Copies the feed link and confirms with a toast when the copy worked. |
| `done()` | 144 | Shows the "Link copied" toast. |
| `reset(id)` | 158 | Rotate the token. Confirmed first, because "Reset" does not convey that every device already subscribed silently stops updating until it is given the new link. |
| `go(ok)` | 165 | Resets the feed token through the endpoint when confirmed, then shows the new link and calls the mount's onReset. |
| `forget(id)` | 195 | Drop the cached URL so the next `open()` re-fetches (after a revoke). |

## Loaded by

- [calendar/index.php](/code/files/calendar/index.php.md)
- [system/preferences/index.php](/code/files/system/preferences/index.php.md)

