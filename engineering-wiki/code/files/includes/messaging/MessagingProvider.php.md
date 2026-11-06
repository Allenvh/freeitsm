# includes/messaging/MessagingProvider.php

> PHP - 272 lines

MessagingProvider — the provider-agnostic contract for a chat channel (WhatsApp today; SMS/others later). Everything above this line — webhook ingestion, ticket creation, the reply path — talks to this interface and never knows whether Twilio or Meta Cloud is actually live.

Concrete providers: TwilioProvider, MetaCloudProvider. They are constructed
with a decrypted messaging_channels row (see messaging.php → `messagingProvider()`).

A "normalised inbound message" (the shape parseInbound returns) is:
  [
    'from'           => '+447700900123',   // sender, normalised (+ and digits)
    'to'             => '+14155238886',    // the business number it arrived on
    'body'           => 'Hi, my laptop…',  // plain text
    'profile_name'   => 'Jane Doe',        // sender display name, or ''
    'provider_msg_id'=> 'SMxxxx',          // provider's message id (dedupe)
    'media'          => [ ['url'=>…, 'content_type'=>…], … ],
    'timestamp'      => 1719500000,        // unix seconds, or null
  ]

## Classes

- [MessagingProvider](/code/classes/MessagingProvider.md) (class, line 23) - MessagingProvider — the provider-agnostic contract for a chat channel (WhatsApp today; SMS/others later). Everything above this line — webhook ingestion, ticket creation, the reply path — talks to this interface and never knows whether Twilio or Meta Cloud is actually live.

## How it hangs together

**Included by:** [includes/messaging/FreeitsmProvider.php](/code/files/includes/messaging/FreeitsmProvider.php.md), [includes/messaging/MattermostProvider.php](/code/files/includes/messaging/MattermostProvider.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/messaging/MetaCloudProvider.php](/code/files/includes/messaging/MetaCloudProvider.php.md), [includes/messaging/SlackProvider.php](/code/files/includes/messaging/SlackProvider.php.md), [includes/messaging/TeamsProvider.php](/code/files/includes/messaging/TeamsProvider.php.md), [includes/messaging/TelegramProvider.php](/code/files/includes/messaging/TelegramProvider.php.md), [includes/messaging/TwilioProvider.php](/code/files/includes/messaging/TwilioProvider.php.md)

**Calls:** [sslApplyCurl()](/code/functions/sslApplyCurl.md)

