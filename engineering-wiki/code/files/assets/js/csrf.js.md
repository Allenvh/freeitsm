# assets/js/csrf.js

> JavaScript - 121 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [CSRF Protection Developer Guide](https://github.com/edmozley/freeitsm/wiki/CSRF-Protection-Developer-Guide)

Adds the session's CSRF token to every request this page sends to FreeITSM (S4 - see includes/csrf.php). Loaded into every page's <head> by the server, before any other script, so code written before tokens existed - and code written after, by anyone - is covered without knowing about it.

`fetch()`                 X-CSRF-Token header
  XMLHttpRequest          X-CSRF-Token header
  <form method="post">    hidden _csrf_token field (on submit, and form.submit())
  navigator.sendBeacon()  ?_csrf= in the URL - a beacon cannot carry headers
  EventSource             ?_csrf= in the URL - neither can a stream

Only same-origin requests get the token: it must never be sent to another site.
GET and HEAD are left alone, except for EventSource, whose streams can write.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `sameOrigin(url)` | 30 | Whether a URL points at this site - the token is never sent anywhere else. |
| `withQuery(url)` | 34 | Adds ?_csrf=token to a same-origin URL that does not already have it; any other URL comes back unchanged. |
| `fetch(input, init)` | 49 | Wraps `fetch()` to add the X-CSRF-Token header to same-origin requests that are not GET, HEAD or OPTIONS. |
| `open(method, url)` | 69 | Notes whether this XMLHttpRequest needs the token (same origin, unsafe method). |
| `setRequestHeader(name)` | 75 | Notes when the caller sets the CSRF header itself, so `send()` does not add a second one. |
| `send()` | 80 | Adds the X-CSRF-Token header before sending, when `open()` decided it was needed. |
| `stamp(form)` | 87 | ── plain HTML forms ───────────────────────────────────────────────────── |
| `sendBeacon(url, data)` | 110 | Wraps navigator.sendBeacon() to carry the token in the URL, as a beacon cannot send headers. |
| `ES(url, cfg)` | 115 | Stand-in EventSource constructor that carries the token in the URL, as a stream cannot send headers. |

