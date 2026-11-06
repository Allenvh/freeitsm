# api/messaging/webhook.php

> API endpoint - 212 lines

Public inbound webhook for messaging channels (WhatsApp via Twilio / Meta Cloud).

POST /api/messaging/webhook.php?channel=<id>

This is the channel equivalent of the email importer, but inverted: instead of
us polling a mailbox, the provider PUSHES messages here. There is no session —
the request comes from Twilio/Meta, so authenticity is established by the
provider's own signature (direct mode) or a shared relay secret (relay mode).

Two ingress modes (messaging_channels.ingress_mode), per the configurable design:
  - 'direct' : the provider hits this URL directly (install exposes HTTPS, e.g.
               via ngrok in dev). Verified by the provider signature.
  - 'relay'  : a hosted relay forwards the verbatim request. Verified by the
               X-FreeITSM-Relay-Secret header matching the channel's relay_secret.
               (The relay itself is Phase 2; this endpoint already accepts it.)

Always responds 200 quickly on success so the provider doesn't retry; auth
failures return 403 and unknown channels 404.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/webhook.php` |
| Protected by | Signed-in analyst (session check) (via includes/i18n.php) |
| Reads | `GET channel`, `GET ping` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [webhookHeaders()](/code/functions/webhookHeaders.md) | 29 | Request headers with lower-cased keys (getallheaders fallback for non-Apache). |
| [webhookFullUrl()](/code/functions/webhookFullUrl.md) | 48 | Reconstruct the exact public URL the provider called (honours ngrok proxy headers). |
| [webhookFail()](/code/functions/webhookFail.md) | 71 | Sends an HTTP status and {success: false, error} as JSON, then stops. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ingestInboundMessage()](/code/functions/ingestInboundMessage.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [messagingProvider()](/code/functions/messagingProvider.md)

