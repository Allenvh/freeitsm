# includes/messaging/MetaCloudProvider.php

> PHP - 373 lines

MetaCloudProvider — WhatsApp via Meta's WhatsApp Cloud API (direct, no BSP).

Cheapest long-term path, but requires Meta business verification before it can
send/receive — which is why TwilioProvider is the recommended first build. The
class exists now so "abstract both providers" is real from day one; the Twilio
path is the tested one for Phase 1.

Credentials JSON (messaging_channels.credentials, encrypted at rest):
  { "phone_number_id": "1234567890", "access_token": "EAAxxxx", "app_secret": "xxxx" }
messaging_channels.verify_token holds the hub.verify_token used for the GET
subscription handshake.

Inbound: Meta POSTs JSON (entry[].changes[].value.messages[]). Authenticity is
the X-Hub-Signature-256 header: 'sha256=' . HMAC-SHA256(app_secret, rawBody).

## Classes

- [MetaCloudProvider](/code/classes/MetaCloudProvider.md) (class, line 21)

## How it hangs together

**Includes:** [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md)

**Included by:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [messagingExtForMime()](/code/functions/messagingExtForMime.md), [messagingMediaCategory()](/code/functions/messagingMediaCategory.md)

