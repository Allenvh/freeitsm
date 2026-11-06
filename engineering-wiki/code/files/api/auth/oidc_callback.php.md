# api/auth/oidc_callback.php

> API endpoint - 646 lines

SSO login callback. GET ?code=...&state=... (the provider redirects the browser here)

Completes the OpenID Connect login:
  1. validate `state` (CSRF), exchange the code for tokens (PKCE),
  2. validate the ID token (signature/JWKS, issuer, audience, nonce),
  3. resolve the analyst: existing link (provider+sub) -> email match ->
     just-in-time create (if the provider allows it),
  4. enforce STRICT isolation: an analyst may only sign in via the provider
     they're assigned to,
  5. set the session directly (SSO users skip the local TOTP/MFA step).

## The endpoint

| | |
|---|---|
| URL | `api/auth/oidc_callback.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `POST action`, `POST csrf`, `GET error`, `GET error_description`, `GET state`, `GET code` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ssoBail()](/code/functions/ssoBail.md) | 27 | Bounce back to the originating portal's login page with an error message. |
| [oidcLoadAnalyst()](/code/functions/oidcLoadAnalyst.md) | 329 | Loads one analyst row by id, or null when there is none. |
| [ssLoadUser()](/code/functions/ssLoadUser.md) | 340 | Loads one self-service user row by id, or null when there is none. |
| [oidcSyncProfile()](/code/functions/oidcSyncProfile.md) | 363 | Copy profile details from the identity provider onto a local account. |
| [oidcExtractProfileAttributes()](/code/functions/oidcExtractProfileAttributes.md) | 411 | Pull the five profile fields out of OIDC claims. Each IdP names them its own way (Entra `jobTitle`/`businessPhones`, Okta `title`, Keycloak `job_title`, the standard `phone_number`), so each field tries the known spellings in turn. Values are trimmed and cut to the column length; missing = null. |
| [completeSelfServiceSso()](/code/functions/completeSelfServiceSso.md) | 473 | Complete an SSO sign-in for the SELF-SERVICE portal. |
| [oidcCreateAnalyst()](/code/functions/oidcCreateAnalyst.md) | 597 | Create a new analyst from SSO claims, assigned to the given provider. The local password is set to an unusable random hash (SSO users sign in via the IdP, not a local password). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 |  | read [auth_providers](/code/tables/auth_providers.md) |  |
| 173 |  | read [analyst_sso_identities](/code/tables/analyst_sso_identities.md) |  |
| 195 |  | **update** [analyst_sso_identities](/code/tables/analyst_sso_identities.md) (`last_login_datetime`, `email`) |  |
| 204 |  | read [analysts](/code/tables/analysts.md) |  |
| 264 |  | **insert** [analyst_sso_identities](/code/tables/analyst_sso_identities.md) (`analyst_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime`) |  |
| 285 |  | **update** [analysts](/code/tables/analysts.md) (`last_login_datetime`, `failed_login_count`) |  |
| 330 | `oidcLoadAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 341 | `ssLoadUser` | read [users](/code/tables/users.md) |  |
| 478 | `completeSelfServiceSso` | read [user_sso_identities](/code/tables/user_sso_identities.md) |  |
| 496 | `completeSelfServiceSso` | **update** [user_sso_identities](/code/tables/user_sso_identities.md) (`last_login_datetime`, `email`) |  |
| 505 | `completeSelfServiceSso` | read [users](/code/tables/users.md) |  |
| 515 | `completeSelfServiceSso` | **update** [users](/code/tables/users.md) (`auth_provider_id`) |  |
| 540 | `completeSelfServiceSso` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `job_title`, `department`, `office`, `phone`, `mobile`, `auth_provider_id`, `tenant_id`, `created_at`) |  |
| 555 | `completeSelfServiceSso` | **insert** [user_sso_identities](/code/tables/user_sso_identities.md) (`user_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime`) |  |
| 603 | `oidcCreateAnalyst` | read [analysts](/code/tables/analysts.md) |  |
| 624 | `oidcCreateAnalyst` | **insert** [analysts](/code/tables/analysts.md) (`username`, `password_hash`, `full_name`, `email`, `job_title`, `department`, `phone`, `mobile`, `is_active`, `created_datetime`, `auth_provider_id`, `can_access_all_modules`) |  |
| 635 | `oidcCreateAnalyst` | **insert** [analyst_modules](/code/tables/analyst_modules.md) (`analyst_id`, `module_key`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/oidc.php](/code/files/includes/oidc.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/landing.php](/code/files/includes/landing.php.md), [includes/sso_claims.php](/code/files/includes/sso_claims.php.md), [includes/sso_identity.php](/code/files/includes/sso_identity.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAnalystAllowedModules()](/code/functions/getAnalystAllowedModules.md), [landingRefreshCookieFromPreference()](/code/functions/landingRefreshCookieFromPreference.md), [oidcDiscover()](/code/functions/oidcDiscover.md), [oidcExchangeCode()](/code/functions/oidcExchangeCode.md), [oidcFetchUserInfo()](/code/functions/oidcFetchUserInfo.md), [oidcGetProvider()](/code/functions/oidcGetProvider.md), [oidcValidateIdToken()](/code/functions/oidcValidateIdToken.md), [requesterIsActive()](/code/functions/requesterIsActive.md), [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md), [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md), [ssoApplyGroupAccess()](/code/functions/ssoApplyGroupAccess.md), [ssoApplyJitCompanies()](/code/functions/ssoApplyJitCompanies.md), [ssoClaimsReady()](/code/functions/ssoClaimsReady.md), [ssoClaimValues()](/code/functions/ssoClaimValues.md), [ssoClearDanglingLink()](/code/functions/ssoClearDanglingLink.md), [ssoInGroup()](/code/functions/ssoInGroup.md), [ssoJitStartForTeams()](/code/functions/ssoJitStartForTeams.md), [ssoRememberGroups()](/code/functions/ssoRememberGroups.md)

