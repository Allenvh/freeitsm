# includes/csrf.php

> PHP - 301 lines

Cross-site request forgery protection (S4) - the token, the origin check, and how the token reaches every page.

WHAT THIS STOPS. Another website making a signed-in person's browser send a
request to FreeITSM - "add this analyst", "change this setting" - with the
login cookie attached. The session cookie is already SameSite=Lax, which stops
the classic cross-site form post in current browsers. This adds two more layers,
because Lax is not the whole answer:
  - "same-site" is wider than "same-origin": helpdesk.example.com and
    www.example.com are the same SITE, so a page on a sibling subdomain (an MSP
    hosting customer sites under its own domain) gets the cookie sent;
  - older browsers, and security reviews, expect a token.

TWO CHECKS, BOTH CENTRAL (csrfEnforce(), run from request_guard.php on every
request that loads functions.php):
  1. ORIGIN. A state-changing request that carries the session cookie must say
     it came from this origin. Browsers label every request (Sec-Fetch-Site, or
     Origin); a cross-site or same-site-but-other-origin request is refused. A
     request with neither header is not from a browser, so there is no victim
     cookie to abuse and it passes.
  2. TOKEN. A state-changing request from a SIGNED-IN session must carry the
     session's token - as the X-CSRF-Token header (fetch, XHR), a `_csrf_token`
     form field (plain forms), or `_csrf` in the query string (sendBeacon and
     EventSource, which cannot set headers).

🔑 NOBODY HAS TO REMEMBER ANYTHING. Endpoints are protected by being reached,
not by opting in, and pages get the token and assets/js/csrf.js injected into
their <head> by `csrfStartPageInjection()`. csrf.js adds the token to every
same-origin request the page makes, whoever wrote the call.

NOT CHECKED: GET/HEAD/OPTIONS (they must not change anything - see
`csrfRequireToken()` for the few that do), the CLI, and callers that are
deliberately cross-origin and never use the login cookie (CSRF_EXEMPT_PATHS).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [csrfRequestPath()](/code/functions/csrfRequestPath.md) | 63 | The request's path relative to the app root, e.g. "api/tickets/save.php". |
| [csrfIsExempt()](/code/functions/csrfIsExempt.md) | 78 | True when the path is on CSRF_EXEMPT_PATHS - an exact file, or anything under an entry ending in '/'. |
| [csrfIsStateChanging()](/code/functions/csrfIsStateChanging.md) | 87 | True for any request method other than GET, HEAD and OPTIONS. |
| [csrfHasSessionCookie()](/code/functions/csrfHasSessionCookie.md) | 94 | Did this request arrive with a session cookie at all? Without one there is nothing to forge. |
| [csrfSessionIsAuthenticated()](/code/functions/csrfSessionIsAuthenticated.md) | 100 | Is the session signed in - an analyst or a portal user? |
| [csrfToken()](/code/functions/csrfToken.md) | 110 | The session's token, created if missing. Works whether the session is open, or was opened read_and_close (then it is reopened just long enough to save). Returns '' when there is no session to keep it in. |
| [csrfRotate()](/code/functions/csrfRotate.md) | 132 | A new token - at sign-in, so a token seen before login is worthless after it. |
| [csrfProvidedToken()](/code/functions/csrfProvidedToken.md) | 140 | The token the request carried, from the header, a form field, or the query string. |
| [csrfOwnHosts()](/code/functions/csrfOwnHosts.md) | 155 | This app's own host[:port], as the browser addresses it: the Host header, and - only when asked, because it costs a query - the configured public address (for a proxy that rewrites Host). |
| [csrfSameOrigin()](/code/functions/csrfSameOrigin.md) | 180 | Did this request come from this app's own pages? true / false, or null when the browser did not say (not a browser, or a very old one). |
| [csrfRefuse()](/code/functions/csrfRefuse.md) | 214 | Refuse, in the shape the caller expects: JSON for the API, a short page for a form post. |
| [csrfEnforce()](/code/functions/csrfEnforce.md) | 239 | The central check. Runs on include of request_guard.php (so on every request that loads functions.php), after the endpoint has started its session. |
| [csrfRequireToken()](/code/functions/csrfRequireToken.md) | 257 | For the rare GET that changes something and is called by the app itself (an EventSource stream that writes, for instance): require the token explicitly. |
| [csrfHeadTags()](/code/functions/csrfHeadTags.md) | 267 | The tags every page needs: the token and the script that sends it. |
| [csrfStartPageInjection()](/code/functions/csrfStartPageInjection.md) | 283 | Put `csrfHeadTags()` into every HTML page without editing each one: the page's output is buffered and the tags are inserted straight after its <head>. Pages only - an API response, a file, a stream and anything that is not HTML pass through untouched. |

## How it hangs together

**Includes:** [includes/public_url.php](/code/files/includes/public_url.php.md)

**Included by:** [includes/request_guard.php](/code/files/includes/request_guard.php.md), [includes/session_security.php](/code/files/includes/session_security.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [publicBaseUrlSetting()](/code/functions/publicBaseUrlSetting.md)

