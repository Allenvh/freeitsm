# api/messaging/slack_manifest.php

> API endpoint - 59 lines

API Endpoint: the Slack app manifest for one channel.

GET slack_manifest.php?channel=<id>

Returns the JSON a person pastes into Slack's "Create an app → From a
manifest" flow, the webhook URL baked into it, and — if this install is not
reachable from the internet — a plain sentence saying so BEFORE they spend ten
minutes discovering it from Slack's own error message.

⚠️ Read-only and returns NO secrets. It contains the public webhook URL and
the scope list, both of which are visible in Slack afterwards anyway.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/slack_manifest.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MESSAGING (requireCapabilityJson) |
| Reads | `GET channel` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/messaging/slack_manifest.php](/code/files/includes/messaging/slack_manifest.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [messagingAdminMayAdministerChannel()](/code/functions/messagingAdminMayAdministerChannel.md), [messagingWebhookUrl()](/code/functions/messagingWebhookUrl.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [slackManifestJson()](/code/functions/slackManifestJson.md), [slackWebhookUrlProblem()](/code/functions/slackWebhookUrlProblem.md)

