# includes/landing.php

> PHP - 185 lines

Where an unauthenticated visitor lands (discussion #63).

FreeITSM has two front doors: the analyst login and the self-service portal.
Historically the root URL always sent people to the analyst login, which is
the wrong first impression on an install where most humans are end users.

Two levels, most specific first:
  1. a per-analyst preference, cached in a cookie so it is readable BEFORE
     anyone has logged in
  2. an install-wide default set by an administrator

⚠️ THE STORED VALUE IS A KEY, NEVER A PATH. `landingTargets()` is the only
place a URL exists. This matters: the setting drives a redirect on the single
most-visited URL in the product, so accepting a path from the database — or,
worse, from a cookie — would be an open redirect on the front door. Every
value coming from outside is validated against the key list and anything
unrecognised falls back to the default.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [landingTargets()](/code/functions/landingTargets.md) | 39 | The only place a landing key maps to a URL. |
| [landingIsValid()](/code/functions/landingIsValid.md) | 58 | Is this a landing key we recognise? Everything from outside goes through here. |
| [landingInstallDefault()](/code/functions/landingInstallDefault.md) | 64 | The install-wide default, or 'analyst' when unset — the historical behaviour. |
| [landingResolve()](/code/functions/landingResolve.md) | 84 | Where should this visitor go? Returns a relative path from the app root. |
| [landingSetCookie()](/code/functions/landingSetCookie.md) | 108 | Write (or clear) the cookie that caches an analyst's choice. |
| [landingRefreshCookieFromPreference()](/code/functions/landingRefreshCookieFromPreference.md) | 144 | Re-issue the cookie from the analyst's stored preference, on login. |
| [landingOnPreferenceSaved()](/code/functions/landingOnPreferenceSaved.md) | 178 | Hook for api/system/set_user_preference.php. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 67 | `landingInstallDefault` | read [system_settings](/code/tables/system_settings.md) |  |
| 151 | `landingRefreshCookieFromPreference` | read [user_preferences](/code/tables/user_preferences.md) |  |

## How it hangs together

**Includes:** [includes/session_security.php](/code/files/includes/session_security.php.md)

**Included by:** [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md), [api/myaccount/verify_login_otp.php](/code/files/api/myaccount/verify_login_otp.php.md), [api/system/get_branding.php](/code/files/api/system/get_branding.php.md), [api/system/save_branding.php](/code/files/api/system/save_branding.php.md), [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md), [index.php](/code/files/index.php.md), [system/preferences/index.php](/code/files/system/preferences/index.php.md)

**Calls:** [requestIsHttps()](/code/functions/requestIsHttps.md)

