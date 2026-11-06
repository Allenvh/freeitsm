# includes/messaging/SlackProvider.php

> PHP - 566 lines

SlackProvider — Slack as a messaging channel, via a Slack app the CUSTOMER owns.

⚠️ Deliberately NOT an "integration provider" in the Jira / Azure DevOps sense.
Slack has no work items and no states, so none of that engine applies. It is a
conversation channel, exactly like WhatsApp and web chat, and it rides the same
MessagingProvider contract: verify the webhook, parse an inbound message, send a
reply, download a file.

── BRING YOUR OWN APP ────────────────────────────────────────────────────────
There is no FreeITSM-hosted Slack app and there never will be. Publishing one
would mean we host the OAuth redirect and every install's events flow through
our server — permanent infrastructure, and we would become a data processor for
other people's companies. Instead the customer creates an app in their own
workspace from the manifest we generate (slack_manifest.php), and their Slack
talks straight to their FreeITSM. Nothing touches us.

Credentials JSON (messaging_channels.credentials, encrypted at rest):
  {
    "bot_token":      "xoxb-…",   // Bot User OAuth Token
    "signing_secret": "…",        // Basic Information → Signing Secret
    "watch_channel":  "C08ABCDE"  // optional: only ingest from this channel
  }
messaging_channels.channel_ref holds the Slack team (workspace) id.

Inbound: Slack POSTs Events API JSON. Authenticity is the v0 signature — see
verifyWebhook, which also enforces the replay window Slack requires.

## Classes

- [SlackProvider](/code/classes/SlackProvider.md) (class, line 33)

## How it hangs together

**Includes:** [includes/messaging/MessagingProvider.php](/code/files/includes/messaging/MessagingProvider.php.md)

**Included by:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Calls:** [messagingExtForMime()](/code/functions/messagingExtForMime.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Traps

> [!WARNING]
> **Line 302, in `SlackProvider::downloadMedia`:** trap: HTML back means the token wasn't accepted.

