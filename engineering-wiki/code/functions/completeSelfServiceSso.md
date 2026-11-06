# completeSelfServiceSso()

Complete an SSO sign-in for the SELF-SERVICE portal.

Mirrors the analyst flow against the requester `users` table:
  1) existing link by (provider, sub),
  2) match an existing requester by verified email,
  3) just-in-time create (if the provider allows it).
Then sets the self-service session (SSO bypasses local TOTP) and lands in
the portal. Never returns — it redirects and exits.

Also reached from the ANALYST login, when someone with no analyst account
is sent on to the portal (the provider's analyst_fallback_mode, #155).

Deliberate difference from analysts: a requester matched by verified email
who is still UNASSIGNED is auto-claimed onto this provider, rather than
rejected. Requesters are low-privilege and self-onboarding (a ticket-created
contact starts passwordless and unassigned), so we don't require an admin to
pre-enrol every customer. An already-assigned requester is still strictly
isolated to their own provider.

```php
function completeSelfServiceSso(
    PDO $conn,
    array $provider,
    int $providerId,
    string $sub,
    string $email,
    bool $emailVerified,
    string $name,
    array $tokens,
    array $claims = []
): void
```

Defined in [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md), line 473.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$provider` | `array` |  | the sso_providers row being signed in with |
| `$providerId` | `int` |  | that provider's id |
| `$sub` | `string` |  | the user's subject (sub claim) at the identity provider - never empty here |
| `$email` | `string` |  | the email claim, lower-cased and trimmed; empty when the provider sent none |
| `$emailVerified` | `bool` |  | true when the provider vouches for the email (or the claim is missing and not required) |
| `$name` | `string` |  | the display name worked out from the claims |
| `$tokens` | `array` |  | the token endpoint response; id_token is kept in the session for single logout |
| `$claims` | `array` | `[]` | the validated claims, used for profile sync |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 478 | `completeSelfServiceSso` | read [user_sso_identities](/code/tables/user_sso_identities.md) |  |
| 496 | `completeSelfServiceSso` | **update** [user_sso_identities](/code/tables/user_sso_identities.md) (`last_login_datetime`, `email`) |  |
| 505 | `completeSelfServiceSso` | read [users](/code/tables/users.md) |  |
| 515 | `completeSelfServiceSso` | **update** [users](/code/tables/users.md) (`auth_provider_id`) |  |
| 540 | `completeSelfServiceSso` | **insert** [users](/code/tables/users.md) (`email`, `display_name`, `job_title`, `department`, `office`, `phone`, `mobile`, `auth_provider_id`, `tenant_id`, `created_at`) |  |
| 555 | `completeSelfServiceSso` | **insert** [user_sso_identities](/code/tables/user_sso_identities.md) (`user_id`, `provider_id`, `subject`, `email`, `linked_datetime`, `last_login_datetime`) |  |

## Calls

[oidcExtractProfileAttributes()](/code/functions/oidcExtractProfileAttributes.md), [oidcSyncProfile()](/code/functions/oidcSyncProfile.md), [requesterIsActive()](/code/functions/requesterIsActive.md), [resolveTenantForNewUser()](/code/functions/resolveTenantForNewUser.md), [sessionPromoteToAuthenticated()](/code/functions/sessionPromoteToAuthenticated.md), [ssLoadUser()](/code/functions/ssLoadUser.md), [ssoBail()](/code/functions/ssoBail.md), [ssoClearDanglingLink()](/code/functions/ssoClearDanglingLink.md)

## Called from

- [api/auth/oidc_callback.php](/code/files/api/auth/oidc_callback.php.md)

