# includes/session_security.php

> PHP - 181 lines

Session hardening.

⚠️ WHY THIS EXISTS. A security review in August 2026 found three gaps that
compound:

  1. `session_regenerate_id` appeared ZERO times in the whole tree. Every point
     that establishes identity — analyst login, MFA, OIDC, portal login, email
     verification — carried on using the session ID the browser arrived with.
     Anyone who can set a victim's session ID before they log in then shares the
     logged-in session. Changing a password did not regenerate either, so a stolen
     session survived the user's own remediation.
  2. No `session_set_cookie_params` call anywhere and no php.ini shipped, so PHP's
     compiled defaults applied: cookie_httponly=0, cookie_samesite="" and
     use_strict_mode=0 — the last meaning PHP would happily ADOPT an
     attacker-chosen session ID rather than minting a fresh one.
  3. No CSRF tokens. SameSite is what stands in for them; unset, Chrome applies
     its own Lax default but Firefox and Safari do not.

Missing HttpOnly was the worst of the three: it means any XSS anywhere in a
21-module application yields the session cookie itself rather than being confined
to the page it fired on.

── Why the ini settings are NOT set here ────────────────────────────────────
Cookie parameters only take effect BEFORE `session_start()`, and 809 of the 818
files that call `session_start()` do so before they include config.php — so there
is no PHP file early enough in the request to be a hook. The ini settings are
therefore shipped as configuration that the SAPI reads for us:

  .user.ini      PHP-FPM / CGI / FastCGI — covers nginx and IIS, which is the
                 case the review specifically called out as unprotected
  .htaccess      Apache with mod_php, which does not read .user.ini
  docker/php.ini baked into the image

`session.cookie_secure` is deliberately absent from all three: it cannot be made
conditional in a static file, and turning it on for an install served over plain
HTTP (which plenty of internal service desks are) would stop the cookie being
sent at all — an instant lockout. It is applied at runtime below, where the
scheme is actually known.

── And why this file exists as well ─────────────────────────────────────────
A server that reads none of those three files would silently have no protection,
which is exactly the failure mode the review found. So the moment a session
becomes an AUTHENTICATED one, the cookie is re-issued explicitly with the right
attributes, regardless of what the server was configured to do. That is the
cookie that matters.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [requestIsHttps()](/code/functions/requestIsHttps.md) | 58 | Is this request actually over HTTPS? |
| [requestScheme()](/code/functions/requestScheme.md) | 90 | 'https' or 'http' — the scheme to put on an absolute URL built from this request. |
| [sessionCookieOptions()](/code/functions/sessionCookieOptions.md) | 103 | The attributes an authenticated session cookie must carry. |
| [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md) | 130 | Call at every point that turns an anonymous session into an identified one, and whenever a password changes. |
| [sessionCookieParamsAreHardened()](/code/functions/sessionCookieParamsAreHardened.md) | 162 | Did the server's own configuration already give the session cookie the attributes we require? (i.e. did one of .user.ini / .htaccess / docker php.ini apply?) |

## How it hangs together

**Includes:** [includes/csrf.php](/code/files/includes/csrf.php.md)

**Included by:** [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md), [includes/calendar_sync/push.php](/code/files/includes/calendar_sync/push.php.md), [includes/csat.php](/code/files/includes/csat.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/landing.php](/code/files/includes/landing.php.md), [includes/oidc.php](/code/files/includes/oidc.php.md), [includes/public_url.php](/code/files/includes/public_url.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md)

**Calls:** [csrfRotate()](/code/functions/csrfRotate.md)

