# includes/messaging/slack_manifest.php

> PHP - 146 lines

The Slack app manifest for THIS install.

FreeITSM does not publish a Slack app. There is no "Add to Slack" button and
no FreeITSM-hosted OAuth service, deliberately: publishing one would mean this
project ran a server that every customer's Slack traffic passed through,
forever, and became a data processor for other people's companies. Instead the
customer creates an app in their own workspace, and it talks straight to their
own FreeITSM.

A manifest turns that from a page of instructions into one paste. Slack's
"Create an app → From a manifest" flow takes this JSON and sets the name,
scopes, event subscriptions and request URL in one go — so nobody has to hunt
through the Slack UI ticking permission boxes, which is where this kind of
setup normally goes wrong.

⚠️ The request URL is baked in per channel, because the webhook endpoint is
per-channel (`webhook.php?channel=<id>`). So the manifest can only be built
AFTER the channel row exists — which is why the settings page saves first and
offers the manifest second.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [slackManifestScopes()](/code/functions/slackManifestScopes.md) | 34 | The scopes we ask for, and — just as importantly — why. |
| [slackBuildManifest()](/code/functions/slackBuildManifest.md) | 53 | Build the manifest for one channel row. |
| [slackManifestJson()](/code/functions/slackManifestJson.md) | 107 | The manifest as the JSON string a person pastes into Slack. Pretty-printed because a human reads it before pasting, and unescaped slashes because an escaped URL (https:\/\/…) looks broken and invites "fixing". |
| [slackWebhookUrlProblem()](/code/functions/slackWebhookUrlProblem.md) | 126 | Is this install's public URL usable as a Slack request URL? |

## How it hangs together

**Includes:** [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md)

**Included by:** [api/messaging/slack_diagnose.php](/code/files/api/messaging/slack_diagnose.php.md), [api/messaging/slack_manifest.php](/code/files/api/messaging/slack_manifest.php.md), [system/integrations/slack.php](/code/files/system/integrations/slack.php.md)

