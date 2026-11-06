# api/auth/oidc_login.php

> API endpoint - 85 lines

SSO login entry point. GET ?provider=<id>

Starts an OpenID Connect Authorization-Code + PKCE login: generates the
state / nonce / PKCE verifier, stashes them in the session, then redirects
the browser to the provider's authorization endpoint.

## The endpoint

| | |
|---|---|
| URL | `api/auth/oidc_login.php` |
| Protected by | Signed-in analyst (session check) (via includes/csrf.php) |
| Reads | `GET portal`, `GET provider` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ssoBail()](/code/functions/ssoBail.md) | 26 | Bounce back to the right portal's login page with an error message. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/oidc.php](/code/files/includes/oidc.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [oidcBase64Url()](/code/functions/oidcBase64Url.md), [oidcBuildAuthUrl()](/code/functions/oidcBuildAuthUrl.md), [oidcDiscover()](/code/functions/oidcDiscover.md), [oidcGetProvider()](/code/functions/oidcGetProvider.md), [oidcRandomToken()](/code/functions/oidcRandomToken.md)

