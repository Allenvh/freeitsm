# includes/messaging/MattermostProvider.php

> PHP - 408 lines

MattermostProvider — a Mattermost support channel, via an Outgoing Webhook (inbound) and the REST API v4 with a bot account (outbound). Contributed by turbay-a in PR #166; see the Teams and Mattermost developer guide for what changed at merge.

Credentials JSON (messaging_channels.credentials, encrypted at rest):
  { "server_url": "https://mattermost.example.com", "bot_token": "..." }
messaging_channels.channel_ref  = the support channel's id (26 chars)
messaging_channels.verify_token = the outgoing webhook token (encrypted)

── THE SHAPE: SLACK'S ───────────────────────────────────────────────────────
A customer posts in the support channel; the Outgoing Webhook forwards it.
FreeITSM answers IN THE THREAD of that post, exactly as SlackProvider does,
so the customer's next reply - also in the channel - comes back through the
same webhook.

TRAP: never answer a Mattermost customer by direct message.
  Outgoing webhooks only fire for posts in public channels. A DM reply looks
  friendlier, but the customer answers the DM, and that answer never reaches
  FreeITSM - the conversation silently stops. (The branch first did this.)

Reply address (stored as to_recipients, see `messagingReplyAddress()`):
  "<channelId>:<postId>" - the post to thread under. The webhook does not say
  whether a post is itself a reply, so `sendMessage()` asks the API for the
  post's root before threading (Mattermost refuses a root_id that is a reply).

Limits: one support channel per configuration; replies are visible to
everyone in that channel (as Slack's are); inbound files are not downloaded,
because the outgoing webhook does not carry them.

## Classes

- [MattermostProvider](/code/classes/MattermostProvider.md) (class, line 36)

## How it hangs together

**Includes:** [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Included by:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [messagingWebhookUrl()](/code/functions/messagingWebhookUrl.md)

## Traps

> [!WARNING]
> **Line 63, in `MattermostProvider::verifyWebhook`:** compare secrets with `hash_equals()`, never === (timing).

> [!WARNING]
> **Line 116, in `MattermostProvider::parseInbound`:** drop the bot's own posts. Our replies are posted in the same channel, so the webhook hands them straight back; without this every analyst reply would arrive as a new message from "the customer".

> [!WARNING]
> **Line 263:** Mattermost refuses a root_id that is itself a reply, and the outgoing webhook never says which a post is - so ask, rather than threading under whatever post came in last.

> [!WARNING]
> **Line 353:** the email is returned ONLY when Mattermost says it is verified. A server can allow sign-up without proving the address, and an unverified email is just text anyone typed: someone registering as ceo@yourcompany.com would have their chats filed under the real CEO. An empty email (the server hides addresses from this bot) is normal - the person simply gets a named contact instead.

