# includes/oidc.php

> PHP - 325 lines

OIDC (OpenID Connect) helper.

Generic OpenID Connect client for SSO login. Everything is driven by the
provider's discovery document (/.well-known/openid-configuration), so one
code path serves Keycloak, Entra, Okta, Google, Authentik, etc.

Uses the vendored firebase/php-jwt library for ID-token signature
validation against the provider's JWKS (the auth-bug hotspot — never
hand-rolled).

Standard hardening: Authorization Code flow + PKCE (S256), `state` (CSRF)
and `nonce` (replay) validation, issuer + audience + expiry checks.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [oidcRedirectUri()](/code/functions/oidcRedirectUri.md) | 39 | The redirect/callback URI registered in the IdP. Built from the deployment's BASE_URL so it matches whatever path the app is served at. MUST be identical in the login-redirect step and the token exchange. |
| [oidcBase64Url()](/code/functions/oidcBase64Url.md) | 50 | URL-safe base64 (no padding) for PKCE / random tokens. |
| [oidcRandomToken()](/code/functions/oidcRandomToken.md) | 59 | Cryptographically-random URL-safe token. |
| [oidcGetProvider()](/code/functions/oidcGetProvider.md) | 69 | Load a provider row by id, with the client secret decrypted. Returns null if not found. |
| [oidcDiscover()](/code/functions/oidcDiscover.md) | 84 | Fetch + cache (per request) a provider's discovery document. Throws on failure. |
| [oidcIsAbsoluteHttpUrl()](/code/functions/oidcIsAbsoluteHttpUrl.md) | 129 | True only for an absolute http:// or https:// URL with a host part. |
| [oidcBuildAuthUrl()](/code/functions/oidcBuildAuthUrl.md) | 146 | Build the authorization URL to redirect the user's browser to. |
| [oidcExchangeCode()](/code/functions/oidcExchangeCode.md) | 170 | Exchange the authorization code for tokens at the token endpoint (server-to-server back-channel). Returns the decoded token response. Throws on HTTP / provider error. |
| [oidcValidateIdToken()](/code/functions/oidcValidateIdToken.md) | 205 | Validate an ID token: signature (JWKS), issuer, audience, nonce, expiry. Returns the validated claims as an associative array. Throws on any failure. |
| [oidcHttpGet()](/code/functions/oidcHttpGet.md) | 242 | GETs a JSON document (discovery, keys) and returns the body; throws on a network error or any status but 200. |
| [oidcHttpPost()](/code/functions/oidcHttpPost.md) | 270 | POSTs form fields to an OIDC endpoint and returns the body whatever the status, so the caller can read an error reply. |
| [oidcFetchUserInfo()](/code/functions/oidcFetchUserInfo.md) | 299 | Fetch claims from the provider userinfo_endpoint using the access_token. Returns decoded JSON claims array or empty array on failure. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 70 | `oidcGetProvider` | read [auth_providers](/code/tables/auth_providers.md) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/sso_identity.php](/code/files/includes/sso_identity.php.md), [includes/session_security.php](/code/files/includes/session_security.php.md), [includes/vendor/firebase-jwt/src/JWT.php](/code/files/includes/vendor/firebase-jwt/src/JWT.php.md), [includes/vendor/firebase-jwt/src/JWK.php](/code/files/includes/vendor/firebase-jwt/src/JWK.php.md), [includes/vendor/firebase-jwt/src/Key.php](/code/files/includes/vendor/firebase-jwt/src/Key.php.md), [includes/vendor/firebase-jwt/src/JWTExceptionWithPayloadInterface.php](/code/files/includes/vendor/firebase-jwt/src/JWTExceptionWithPayloadInterface.php.md), [includes/vendor/firebase-jwt/src/BeforeValidException.php](/code/files/includes/vendor/firebase-jwt/src/BeforeValidException.php.md), [includes/vendor/firebase-jwt/src/ExpiredException.php](/code/files/includes/vendor/firebase-jwt/src/ExpiredException.php.md), [includes/vendor/firebase-jwt/src/SignatureInvalidException.php](/code/files/includes/vendor/firebase-jwt/src/SignatureInvalidException.php.md)

**Included by:** [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md), [api/auth/oidc_login.php](/code/files/api/auth/oidc_login.php.md), [self-service/logout.php](/code/files/self-service/logout.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [requestScheme()](/code/functions/requestScheme.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

