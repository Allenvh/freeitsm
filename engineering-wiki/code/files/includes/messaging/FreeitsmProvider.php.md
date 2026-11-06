# includes/messaging/FreeitsmProvider.php

> PHP - 65 lines

FreeitsmProvider — the "provider" for the self-hosted website chat channel.

Unlike Twilio/Meta there is no third party to call: a web chat conversation lives
entirely inside FreeITSM. Inbound arrives through the public widget endpoints
(api/webchat/*), not the shared provider webhook, and "sending" a reply is simply
persisting the outbound message — the visitor's browser polls poll.php and picks it
up. So sendMessage does no network I/O; it just hands back a synthetic message id
for the emails.exchange_message_id column (kept unique for dedupe/threading parity
with the other channels).

The class exists so the shared reply path (api/messaging/send_message.php, which
calls messagingProvider(`$channel`)->sendMessage()) works for webchat with no special
casing — the one channel that has no external send still satisfies the contract.

## Classes

- [FreeitsmProvider](/code/classes/FreeitsmProvider.md) (class, line 20)

## How it hangs together

**Includes:** [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md)

**Included by:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

