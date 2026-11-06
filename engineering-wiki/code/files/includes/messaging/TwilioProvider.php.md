# includes/messaging/TwilioProvider.php

> PHP - 328 lines

TwilioProvider — WhatsApp via Twilio's Messaging API.

This is the recommended first-build provider because Twilio offers a WhatsApp
**sandbox**: you can send/receive end-to-end on a dev box (WAMP + ngrok) with
no Meta business verification. The production path is identical once your own
WhatsApp sender is approved.

Credentials JSON (messaging_channels.credentials, encrypted at rest):
  { "account_sid": "ACxxxx", "auth_token": "xxxx" }
The business WhatsApp number is messaging_channels.phone_number (the "From").

Inbound: Twilio POSTs application/x-www-form-urlencoded with From/To/Body/
MessageSid/ProfileName/NumMedia/MediaUrl{n}/MediaContentType{n}. Authenticity is
the X-Twilio-Signature header: base64(HMAC-SHA1(authToken, url + sorted POST kv)).

## Classes

- [TwilioProvider](/code/classes/TwilioProvider.md) (class, line 21)

## How it hangs together

**Includes:** [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md)

**Included by:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [messagingExtForMime()](/code/functions/messagingExtForMime.md)

