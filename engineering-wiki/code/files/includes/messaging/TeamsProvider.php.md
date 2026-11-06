# includes/messaging/TeamsProvider.php

> PHP - 497 lines

TeamsProvider — Microsoft Teams one-to-one chats with the FreeITSM bot, via the Bot Framework. Contributed by turbay-a in PR #166; see the Teams and Mattermost developer guide for what changed at merge.

Credentials JSON (messaging_channels.credentials, encrypted at rest):
  { "app_id": "<Azure Bot App ID>", "app_secret": "...", "tenant_id": "<AAD tenant>" }
The tenant is required for a single-tenant app registration: token requests
to the wrong tenant fail with AADSTS700016.

── INBOUND ──────────────────────────────────────────────────────────────────
Bot Framework POSTs an Activity with `Authorization: Bearer <JWT>`.
`verifyWebhook()` checks, in this order:
  1. the RS256 signature, against Bot Framework's published keys - with the
     vendored firebase/php-jwt, the same library SSO uses (includes/oidc.php),
     not a second hand-written RSA/DER decoder;
  2. issuer, audience (this channel's App ID) and expiry (5 min leeway);
  3. the token's `serviceurl` claim equals the activity's serviceUrl.
     TRAP: never trust a serviceUrl the token does not vouch for. Replies are
     POSTed to it WITH THE BOT'S ACCESS TOKEN, so an activity that could name
     its own serviceUrl could collect that token. Bot Framework requires
     this check; the branch first skipped it;
  4. the serviceUrl is a Microsoft Bot Framework host (belt and braces for 3);
  5. the activity comes from THIS channel's Microsoft 365 tenant, so a person
     in another organisation cannot open tickets by finding the bot.

── THE REPLY ADDRESS ────────────────────────────────────────────────────────
The serviceUrl is PER CONVERSATION (Teams routes by the user's region), so it
travels with the conversation, not the channel: `parseInbound()` sets
  to = "<serviceUrl>|<conversationId>"
ingest stores that as the row's to_recipients and `messagingReplyAddress()`
hands it back to `sendMessage()`.

TRAP: never store the serviceUrl on the channel. The branch first kept the
  latest one in messaging_channels.channel_ref, which answers one user through
  another user's region.

Outbound: POST {serviceUrl}v3/conversations/{conversationId}/activities with
an Azure AD client-credentials token for the Bot Framework scope.

Limits: one-to-one chats only (group chats and channels are ignored, as
Telegram groups are); images only for analyst attachments, because Teams
needs a public https URL for the file (media.php); no read receipts.

## Classes

- [TeamsProvider](/code/classes/TeamsProvider.md) (class, line 59)

## How it hangs together

**Includes:** [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md), [includes/vendor/firebase-jwt/src/JWT.php](/code/files/includes/vendor/firebase-jwt/src/JWT.php.md), [includes/vendor/firebase-jwt/src/JWK.php](/code/files/includes/vendor/firebase-jwt/src/JWK.php.md), [includes/vendor/firebase-jwt/src/Key.php](/code/files/includes/vendor/firebase-jwt/src/Key.php.md), [includes/vendor/firebase-jwt/src/JWTExceptionWithPayloadInterface.php](/code/files/includes/vendor/firebase-jwt/src/JWTExceptionWithPayloadInterface.php.md), [includes/vendor/firebase-jwt/src/BeforeValidException.php](/code/files/includes/vendor/firebase-jwt/src/BeforeValidException.php.md), [includes/vendor/firebase-jwt/src/ExpiredException.php](/code/files/includes/vendor/firebase-jwt/src/ExpiredException.php.md), [includes/vendor/firebase-jwt/src/SignatureInvalidException.php](/code/files/includes/vendor/firebase-jwt/src/SignatureInvalidException.php.md)

**Included by:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [messagingExtForMime()](/code/functions/messagingExtForMime.md)

