# includes/messaging/messaging.php

> PHP - 512 lines

Messaging channels — shared entry point.

Require this one file from any endpoint that deals with WhatsApp/chat channels;
it pulls in the provider classes and exposes the helpers below:

  messagingProvider(`$channel`)        → a MessagingProvider for a (decrypted) row
  loadMessagingChannel(`$conn`, `$id`)   → a channel row with credentials decrypted
  normaliseChannelIdentifier(`$raw`, `$type`) → a sender id, normalised per channel
                                       (phone as '+<digits>'; Slack as 'U…')
  channelWindowOpen(`$lastInboundAt`)  → is the 24h service window still open?

The credentials column is an encrypted JSON blob; loadMessagingChannel decrypts
it and decodes it into `$channel`['credentials'] (an array). Providers read their
own keys out of that array (shapes documented on each provider class).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [messagingProvider()](/code/functions/messagingProvider.md) | 40 | Build the right provider for a channel row (credentials already decrypted into an array by loadMessagingChannel). Throws on an unknown provider. |
| [loadMessagingChannel()](/code/functions/loadMessagingChannel.md) | 69 | Load a messaging_channels row with its credentials decrypted + JSON-decoded. Returns null if not found. Safe to call before db_verify has run the table (returns null rather than throwing). |
| [messagingDecodeCredentials()](/code/functions/messagingDecodeCredentials.md) | 99 | Decrypt + JSON-decode a stored credentials blob into an array (never throws). |
| [messagingPublicBaseUrl()](/code/functions/messagingPublicBaseUrl.md) | 118 | The public base URL (scheme://host[:port]) used to build webhook URLs. Uses the admin-set 'messaging_public_base_url' system setting when present, otherwise derives it from the current request (honouring ngrok/tunnel proxy headers). |
| [messagingWebhookUrl()](/code/functions/messagingWebhookUrl.md) | 144 | The full public webhook URL for a channel — what gets pasted into the provider. The app root is derived from SCRIPT_NAME so it works under any sub-path. |
| [messagingOutboundMediaUrl()](/code/functions/messagingOutboundMediaUrl.md) | 176 | A short-lived, unguessable URL to one outbound attachment's bytes — for a provider (Twilio) that fetches media itself rather than accepting an upload. NOT a general-purpose public file host: the token is bound to this ONE attachment id and expires quickly (checked in media.php), so it only stays reachable for the brief window between sending the message and the provider fetching it — not for as long as the ticket exists. |
| [messagingOutboundMediaToken()](/code/functions/messagingOutboundMediaToken.md) | 209 | HMAC binding one attachment id to one expiry — see `messagingOutboundMediaUrl()` and media.php. |
| [messagingMediaCategory()](/code/functions/messagingMediaCategory.md) | 221 | The category Meta's Media upload endpoint wants for a mime type ('image'\|'video'\|'audio'\|'document'). |
| [normaliseChannelIdentifier()](/code/functions/normaliseChannelIdentifier.md) | 245 | Normalise a sender identifier for storage and matching. |
| [messagingNormaliseLocale()](/code/functions/messagingNormaliseLocale.md) | 296 | Map a raw client-reported language tag (e.g. Telegram's message.from. language_code — the user's own OS/app setting, "uk", "en", "pt-BR", never something they typed) onto one of this install's I18n::SUPPORTED_LOCALES keys. Unrecognised or empty input falls back to English — I18n::tFor() would do that anyway, but resolving it here keeps the fallback visible to the caller (e.g. for deciding whether to bother storing it). |
| [messagingExtForMime()](/code/functions/messagingExtForMime.md) | 322 | A sensible file extension for a media MIME type (for naming downloaded media). |
| [messagingTemplateVarCount()](/code/functions/messagingTemplateVarCount.md) | 352 | How many distinct {{n}} placeholders a template body uses (the highest index). e.g. "Hi {{1}}, ref {{2}}" → 2. |
| [messagingRenderTemplate()](/code/functions/messagingRenderTemplate.md) | 367 | Substitute {{1}}, {{2}}, … in a template body with the given ordered values, for storing/showing what was actually sent. Missing values are left blank. |
| [messagingBuildTranscript()](/code/functions/messagingBuildTranscript.md) | 381 | Build a plain-text transcript of a channel conversation (oldest first), capped to keep token usage predictable. Used by the AI summary / suggested-reply endpoints. Non-channel (email) rows are excluded. |
| [messagingReplyAddress()](/code/functions/messagingReplyAddress.md) | 445 | The address to reply to on a conversation row - to_recipients on threaded channels (Slack, Teams, Mattermost), from_address elsewhere. |
| [channelHasServiceWindow()](/code/functions/channelHasServiceWindow.md) | 458 | True for channels with a reply window (WhatsApp); web chat, Slack, Telegram, Teams and Mattermost have none. |
| [channelWindowOpen()](/code/functions/channelWindowOpen.md) | 468 | True when the last inbound message (UTC) was under 24 hours ago, so a free-form reply is still allowed. |
| [messagingAdminMayAdministerChannel()](/code/functions/messagingAdminMayAdministerChannel.md) | 499 | Authorise a request that acts on ONE messaging channel. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 72 | `loadMessagingChannel` | read [messaging_channels](/code/tables/messaging_channels.md) |  |
| 121 | `messagingPublicBaseUrl` | read [system_settings](/code/tables/system_settings.md) |  |
| 384 | `messagingBuildTranscript` | read [emails](/code/tables/emails.md) |  |
| 505 | `messagingAdminMayAdministerChannel` | read [messaging_channels](/code/tables/messaging_channels.md) |  |

## How it hangs together

**Includes:** [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md), [includes/messaging/TwilioProvider.php](/code/files/includes/messaging/TwilioProvider.php.md), [includes/messaging/MetaCloudProvider.php](/code/files/includes/messaging/MetaCloudProvider.php.md), [includes/messaging/SlackProvider.php](/code/files/includes/messaging/SlackProvider.php.md), [includes/messaging/FreeitsmProvider.php](/code/files/includes/messaging/FreeitsmProvider.php.md), [includes/messaging/TelegramProvider.php](/code/files/includes/messaging/TelegramProvider.php.md), [includes/messaging/TeamsProvider.php](/code/files/includes/messaging/TeamsProvider.php.md), [includes/messaging/MattermostProvider.php](/code/files/includes/messaging/MattermostProvider.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Included by:** [api/messaging/ai_suggest_reply.php](/code/files/api/messaging/ai_suggest_reply.php.md), [api/messaging/ai_summary.php](/code/files/api/messaging/ai_summary.php.md), [api/messaging/delete_channel.php](/code/files/api/messaging/delete_channel.php.md), [api/messaging/get_channels.php](/code/files/api/messaging/get_channels.php.md), [api/messaging/get_templates.php](/code/files/api/messaging/get_templates.php.md), [api/messaging/media.php](/code/files/api/messaging/media.php.md), [api/messaging/save_channel.php](/code/files/api/messaging/save_channel.php.md), [api/messaging/send_attachment.php](/code/files/api/messaging/send_attachment.php.md), [api/messaging/send_message.php](/code/files/api/messaging/send_message.php.md), [api/messaging/send_template.php](/code/files/api/messaging/send_template.php.md), [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md), [api/messaging/slack_manifest.php](/code/files/api/messaging/slack_manifest.php.md), [api/messaging/teams_package.php](/code/files/api/messaging/teams_package.php.md), [api/messaging/telegram_connect.php](/code/files/api/messaging/telegram_connect.php.md), [api/messaging/test_channel.php](/code/files/api/messaging/test_channel.php.md), [api/messaging/webhook.php](/code/files/api/messaging/webhook.php.md), [api/tickets/get_ticket_thread.php](/code/files/api/tickets/get_ticket_thread.php.md), [includes/csat.php](/code/files/includes/csat.php.md), [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), [includes/messaging/slack_manifest.php](/code/files/includes/messaging/slack_manifest.php.md), [includes/messaging/TeamsPackage.php](/code/files/includes/messaging/TeamsPackage.php.md), [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), [system/integrations/slack.php](/code/files/system/integrations/slack.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [getEncryptionKey()](/code/functions/getEncryptionKey.md), [sessionIsAdmin()](/code/functions/sessionIsAdmin.md)

