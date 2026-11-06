# system/integrations/slack.php

> PHP - 542 lines

System — Slack workspaces.

Reached as /system/integrations/slack (see .htaccess), or by provider.php
handing over when the registry says this provider's `kind` is not a tracker.

⚠️ It sits in Integrations because that is where people look for "connect
FreeITSM to X", but underneath it is a MESSAGING CHANNEL: rows live in
`messaging_channels`, the same table as WhatsApp and web chat, and it reuses
api/messaging/*. It has its own page rather than the registry-rendered
credential form because its setup is a different shape — install an app from a
manifest, then paste two secrets back — not "a base URL and a token".

⚠️ The manifest can only be built once the channel row EXISTS, because the
webhook endpoint is per-channel (webhook.php?channel=<id>). That is why the
flow is: name it and save → collect the app → paste the secrets.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [messaging_channels](/code/tables/messaging_channels.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md), [includes/messaging/slack_manifest.php](/code/files/includes/messaging/slack_manifest.php.md)

**Loads scripts:** [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAllTenants()](/code/functions/getAllTenants.md), [integrationsProviderMeta()](/code/functions/integrationsProviderMeta.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [pageTitle()](/code/functions/pageTitle.md), [slackManifestScopes()](/code/functions/slackManifestScopes.md), [t()](/code/functions/t.md)

