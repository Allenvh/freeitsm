# includes/messaging/TelegramProvider.php

> PHP - 591 lines

TelegramProvider — chat via the Telegram Bot API.

Simpler than WhatsApp in two ways that matter here: there is no 24h service
window (a bot may message any chat that has ever messaged it, at any time —
`sendTemplate()` below is therefore just `sendMessage()` with the template body
rendered), and there is no separate business-verification step — a bot token
from @BotFather is immediately live.

Credentials JSON (messaging_channels.credentials, encrypted at rest):
  { "bot_token": "123456789:AA...-the-BotFather-token" }

Inbound: Telegram POSTs JSON (the Update object) to the webhook URL set via
setWebhook. Authenticity is the secret token Telegram echoes back on every
call once configured — X-Telegram-Bot-Api-Secret-Token — which is stored in
messaging_channels.verify_token (the same column Meta uses for its own
verify token; each provider reads it however its own handshake needs).

The identifier used everywhere else in this codebase ('from') is the chat id
— a bare integer, stringified — because that is what `sendMessage()` needs
back to reply. It is NOT a phone number, so `normaliseChannelIdentifier()` has
a 'telegram' branch that leaves it alone rather than running the WhatsApp
digit-stripping rule over it.

## Classes

- [TelegramProvider](/code/classes/TelegramProvider.md) (class, line 29)

## How it hangs together

**Includes:** [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md)

**Included by:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [messagingExtForMime()](/code/functions/messagingExtForMime.md), [messagingRenderTemplate()](/code/functions/messagingRenderTemplate.md)

## Traps

> [!WARNING]
> **Line 524, in `TelegramProvider::setWebhook`:** this list is everything `parseInbound()` reads. Leave one out and Telegram never sends it - no error anywhere. A rating button press is a callback_query; without it the button just shimmers on the customer's phone. Telegram keeps the list a bot was registered with, so a bot connected before a type was added needs Connect pressed again - `testConnection()` checks for that (TELEGRAM_UPDATE_TYPES).

