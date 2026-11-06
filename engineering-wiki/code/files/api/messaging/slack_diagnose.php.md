# api/messaging/slack_diagnose.php

> API endpoint - 306 lines

API Endpoint: a full health check of one Slack channel.

POST slack_diagnose.php   { "id": 11 }

Every check here is one I had to run by hand while getting the first
workspace working. Each corresponds to a real failure, and — this is the
point — most of those failures look like nothing at all: the app is connected,
the token is valid, tickets even arrive, and one thing is quietly wrong.

⚠️ Its own endpoint rather than another mode on test_channel.php. That file is
the shared "test a channel" for every provider (credentials / reachability /
simulate); these checks are Slack-specific and would have made it a file of
two unrelated halves.

Every check returns the same shape so the UI never has to special-case one:
  ['key','label','status' => ok|warn|fail|skip,'detail','fix' => '']

`fix` is the sentence that tells someone what to DO. A diagnostic that says
"missing_scope" and stops has moved the problem, not solved it.

## The endpoint

| | |
|---|---|
| URL | `api/messaging/slack_diagnose.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_MESSAGING (requireCapabilityJson) |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addCheck()](/code/functions/addCheck.md) | 79 | Appends one result row (key, label, status, detail and an optional fix) to the global `$checks` list the diagnosis returns. |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [includes/messaging/slack_manifest.php](/code/files/includes/messaging/slack_manifest.php.md)

**Calls:** [analystCanAccessChannel()](/code/functions/analystCanAccessChannel.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [loadMessagingChannel()](/code/functions/loadMessagingChannel.md), [messagingAdminMayAdministerChannel()](/code/functions/messagingAdminMayAdministerChannel.md), [messagingProvider()](/code/functions/messagingProvider.md), [messagingWebhookUrl()](/code/functions/messagingWebhookUrl.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [slackManifestScopes()](/code/functions/slackManifestScopes.md), [slackWebhookUrlProblem()](/code/functions/slackWebhookUrlProblem.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

