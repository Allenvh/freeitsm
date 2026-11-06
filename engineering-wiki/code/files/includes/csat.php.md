# includes/csat.php

> PHP - 365 lines

CSAT Survey Helpers

Creates a ticket_csat_responses row with a tokenised URL and sends the
survey email via the existing template engine. The token is HMAC-derived
so a leaked database row can't be replayed against a different install
(the per-install secret lives in system_settings.csat_token_secret).

Called from:
  - api/tickets/assign_ticket.php when a ticket transitions into a closed
    status and the global csat_mode is 'auto'
  - api/tickets/request_csat.php when an analyst clicks the manual
    "Request feedback" button (regardless of mode, as long as mode != 'off')

## Functions

| Function | Line | What it does |
|---|--:|---|
| [csatGetSetting()](/code/functions/csatGetSetting.md) | 27 | Read a system_settings key with a default fallback. |
| [csatGenerateToken()](/code/functions/csatGenerateToken.md) | 51 | Generate a token unique to (ticket, response_row). 32-byte random base ensures it's not guessable from ticket id alone; we then HMAC it with the install secret so even a database leak of `token` alone isn't enough — though for the survey URL we just use the random portion since the response row IS the authoritative store. Keeping HMAC available as a verification step in case we ever want to surface the link in places where the DB isn't queried. |
| [csatBuildUrl()](/code/functions/csatBuildUrl.md) | 62 | Build the full public URL the user clicks. Uses the host the request came in on so multi-domain installs (test.example.com vs example.com) work without config changes. |
| [csatTicketChannel()](/code/functions/csatTicketChannel.md) | 125 | The chat a ticket's customer is talking on, ready to ask in - or null, which means "use the email survey": the setting is off, the ticket's latest contact was not a chat, the channel has gone, or (WhatsApp) the 24-hour window has closed and a free-form message cannot be sent. |
| [csatChannelLocale()](/code/functions/csatChannelLocale.md) | 164 | The language to write a customer-facing rating message in. Telegram has the customer's own language_code (stored on the identity link); other channels do not report one, so they get English. |
| [csatSendInChannel()](/code/functions/csatSendInChannel.md) | 184 | Ask a chat customer for a 1-5 rating in their chat. Returns the response id, or throws - having removed its own row - so the caller can fall back to email. |
| [csatPendingRequestForChat()](/code/functions/csatPendingRequestForChat.md) | 221 | The rating request a customer's bare digit answers, or null - in which case the digit is an ordinary message. All of these must hold: - the request was sent IN THIS CHAT (channel_id + channel_from); - it is unanswered and at most CSAT_CHANNEL_REPLY_WINDOW_DAYS old; - the customer has written nothing else in this chat since it was sent. |
| [csatResponseBelongsToChat()](/code/functions/csatResponseBelongsToChat.md) | 250 | Was this request sent to this customer on this channel? Guards a button press against a forged or replayed id. |
| [csatStoredRating()](/code/functions/csatStoredRating.md) | 265 | The rating a survey holds, or null while it is unanswered. |
| [csatRecordRating()](/code/functions/csatRecordRating.md) | 282 | Record a 1-5 rating once. Returns false if it was already answered or out of range. |
| [sendCsatSurvey()](/code/functions/sendCsatSurvey.md) | 306 | Create a CSAT response row and send the survey email. Returns an array: ['result' => <CSAT_RESULT_*>, 'response_id' => int\|null] |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 | `csatGetSetting` | read [system_settings](/code/tables/system_settings.md) |  |
| 131 | `csatTicketChannel` | read [emails](/code/tables/emails.md) |  |
| 169 | `csatChannelLocale` | read [messaging_identity_links](/code/tables/messaging_identity_links.md) |  |
| 190 | `csatSendInChannel` | **insert** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) (`ticket_id`, `token`, `sent_datetime`, `analyst_id`, `created_at`, `channel_id`, `channel_from`) |  |
| 200 | `csatSendInChannel` | **delete** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 224 | `csatPendingRequestForChat` | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 227 | `csatPendingRequestForChat` | read [emails](/code/tables/emails.md) |  |
| 252 | `csatResponseBelongsToChat` | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 267 | `csatStoredRating` | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 287 | `csatRecordRating` | **update** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) (`rating`, `responded_datetime`) |  |
| 314 | `sendCsatSurvey` | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 336 | `sendCsatSurvey` | read [ticket_email_templates](/code/tables/ticket_email_templates.md) |  |
| 353 | `sendCsatSurvey` | **insert** [ticket_csat_responses](/code/tables/ticket_csat_responses.md) (`ticket_id`, `token`, `sent_datetime`, `analyst_id`, `created_at`) |  |

## How it hangs together

**Includes:** [includes/template_email.php](/code/files/includes/template_email.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/session_security.php](/code/files/includes/session_security.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md)

**Included by:** [api/tickets/request_csat.php](/code/files/api/tickets/request_csat.php.md), [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [tickets/csat/survey.php](/code/files/tickets/csat/survey.php.md)

**Calls:** [channelHasServiceWindow()](/code/functions/channelHasServiceWindow.md), [channelWindowOpen()](/code/functions/channelWindowOpen.md), [decryptValue()](/code/functions/decryptValue.md), [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [messagingProvider()](/code/functions/messagingProvider.md), [messagingReplyAddress()](/code/functions/messagingReplyAddress.md), [requestScheme()](/code/functions/requestScheme.md), [sendTemplateEmail()](/code/functions/sendTemplateEmail.md), [templateGetMailboxForTicket()](/code/functions/templateGetMailboxForTicket.md)

## Traps

> [!WARNING]
> **Line 97:** an upgrade must not change who is asked, or how. `csat_in_channel` is OFF after an upgrade (db_verify seeds '0'; a new install's freeitsm.sql seeds '1'). An install that has emailed every survey for months must not start messaging its chat customers the day it upgrades.

> [!WARNING]
> **Line 102:** match a chat reply only to a request sent IN THAT CHAT. channel_id + channel_from record where a request went. Never find "the pending survey" through the emails table alone - that also finds emailed surveys and other chats' requests.

> [!WARNING]
> **Line 107:** no orphan rows. The row exists only to give the buttons an id; if the send fails it is deleted and the email survey is tried instead. With one-survey-per-ticket on, a stray row would mean the customer is never asked.

> [!WARNING]
> **Line 111:** WhatsApp outside its 24-hour window cannot take a free-form message - `csatTicketChannel()` checks `channelWindowOpen()` and falls back to email.

> [!WARNING]
> **Line 206:** a bare digit is usually NOT a rating. "Which floor?" "3" is an answer to the analyst, and a "2" next month starts a new conversation - loosen any of the three conditions and real messages vanish into a survey, with no error anywhere. tests/messaging-teams-mattermost.php checks each one.

