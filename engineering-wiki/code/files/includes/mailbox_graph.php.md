# includes/mailbox_graph.php

> PHP - 442 lines

Shared Microsoft Graph helpers for target mailboxes.

Used by api/tickets/check_mailbox_email.php, send_email.php and
verify_mailbox_folder.php to support the two authentication modes:

  - delegated : OAuth sign-in; the token is a user's, so calls go to /me.
  - app_only  : client-credentials; the app authenticates itself and reads the
                specific /users/<target_mailbox>.

Delegated token refresh stays in each caller (legacy, unchanged). This file only
adds the app-only token + the per-request Graph base path.

Requires config.php (for SSL_VERIFY_PEER) to be loaded already. `$mailbox` must be
the DECRYPTED row (azure_* fields in clear text) — which now includes token_data,
so the cached app-only token read below is plaintext on the way in and re-encrypted
on the way out.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [mailboxAppOnlyToken()](/code/functions/mailboxAppOnlyToken.md) | 31 | App-only access token via the client-credentials flow (no user, no interactive sign-in). Cached in target_mailboxes.token_data until it expires (app-only tokens carry no refresh token — we just re-fetch). |
| [mailboxIdentityFromToken()](/code/functions/mailboxIdentityFromToken.md) | 83 | Pull the signed-in user's address straight out of a Graph access token. The token is a JWT whose payload carries the user's UPN / email — so we can read it offline, with NO Graph permission. This matters because the usual mailbox scopes (Mail.*) do NOT include User.Read, so a /me call 403s and can't tell us who signed in. Returns a lowercased email, or '' if the token has no usable claim. |
| [mailboxDelegatedIdentity()](/code/functions/mailboxDelegatedIdentity.md) | 108 | Who does a (delegated) access token belong to? Returns the lowercased email, or '' if it can't be determined. Used to back-fill authenticated_as for mailboxes that signed in before we started recording it. |
| [mailboxFetchAddresses()](/code/functions/mailboxFetchAddresses.md) | 134 | Fetch ALL email addresses for the signed-in (delegated) mailbox — primary SMTP, UPN and every alias (proxyAddresses). This is how we tell that e.g. ed@ is just an alias of the edmozley@ mailbox: the token only carries the UPN, so we have to ask Graph for the alias list. Needs the lightweight User.Read scope; returns [] if it isn't granted (older mailboxes) so the caller can fall back to the token claim. |
| [mailboxIdentityRecord()](/code/functions/mailboxIdentityRecord.md) | 167 | Build the identity record for a freshly-authenticated delegated token: ['primary' => <display address>, 'addresses' => [every address this mailbox owns]]. Combines the token's own claim (always available, offline) with the Graph alias list (when User.Read is granted). 'primary' is what we show; 'addresses' is what we match the configured target against. |
| [mailboxAcceptedSet()](/code/functions/mailboxAcceptedSet.md) | 182 | The set of addresses a (delegated) mailbox is known to own, read back from the stored columns (authenticated_addresses JSON + authenticated_as). Offline, lowercased. Empty means "we don't know yet" — callers treat that as grandfathered, not a mismatch. |
| [mailboxIdentityMismatch()](/code/functions/mailboxIdentityMismatch.md) | 204 | Offline "is this reading the wrong inbox?" check for delegated Microsoft mailboxes. Returns an error message to show/throw, or null if it's fine. A configured target that matches ANY owned address (primary OR alias) passes; an unknown identity set (legacy mailboxes that predate alias capture) is grandfathered through. |
| [mailboxBackfillIdentity()](/code/functions/mailboxBackfillIdentity.md) | 227 | Populate authenticated_as / authenticated_addresses for a delegated Microsoft mailbox that doesn't have them yet (signed in before we recorded identity, or before alias capture). Returns the (possibly updated) `$mailbox`. No-op for Google and app-only. |
| [mailboxGraphBase()](/code/functions/mailboxGraphBase.md) | 254 | Per-request Graph base path: '/me' (delegated) or '/users/<addr>' (app-only). Set once after the mailbox is loaded; the Graph helpers read it back. |
| [mailboxIsAppOnly()](/code/functions/mailboxIsAppOnly.md) | 268 | Is this a Microsoft mailbox authenticating as itself (client credentials)? There is no signed-in user in that mode, so '/me' is meaningless to Graph and every call has to name the mailbox: /users/<target_mailbox>. Google and IMAP mailboxes always behave as delegated. |
| [mailboxResolveGraphBase()](/code/functions/mailboxResolveGraphBase.md) | 279 | Resolve the base path for a mailbox from its auth_mode (Microsoft only). Google mailboxes always behave as delegated. |
| [mailboxCanSend()](/code/functions/mailboxCanSend.md) | 296 | Is this mailbox capable of sending right now? |
| [mailboxGraphGet()](/code/functions/mailboxGraphGet.md) | 322 | An authenticated Graph GET, shaped for `mailboxResolveFolderId()`. Every caller was writing the same twelve lines of curl. |
| [mailboxResolveFolderId()](/code/functions/mailboxResolveFolderId.md) | 367 | Turn a folder a human typed into the id Graph actually wants (GH #77). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 68 | `mailboxAppOnlyToken` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`token_data`) |  |
| 236 | `mailboxBackfillIdentity` | **update** [target_mailboxes](/code/tables/target_mailboxes.md) (`authenticated_as`, `authenticated_addresses`) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md)

**Included by:** [api/auth/request_password_reset.php](/code/files/api/auth/request_password_reset.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [api/tickets/get_mailboxes.php](/code/files/api/tickets/get_mailboxes.php.md), [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), [api/tickets/set_mailbox_health_dismissed.php](/code/files/api/tickets/set_mailbox_health_dismissed.php.md), [api/tickets/verify_mailbox_folder.php](/code/files/api/tickets/verify_mailbox_folder.php.md), [includes/self_service_email.php](/code/files/includes/self_service_email.php.md), [includes/template_email.php](/code/files/includes/template_email.php.md)

**Calls:** [encryptValue()](/code/functions/encryptValue.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

