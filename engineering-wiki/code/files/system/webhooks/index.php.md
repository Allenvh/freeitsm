# system/webhooks/index.php

> PHP - 765 lines

System — Webhooks.

Admin view over the outbound-webhook delivery queue (webhook_deliveries):
  1. A prominent SETUP card that makes it unmistakable what has to be in place
     for webhooks to actually leave the building — chiefly the background
     delivery worker (cron/webhook_deliveries.php). We detect live whether that
     worker is running (webhook_cron_last_run) and show the exact command to
     schedule if not.
  2. An OVERVIEW dashboard — 7-day success rate / volume / avg delivery time /
     queued / dead-letter KPIs, a 14-day delivered-vs-failed volume chart, and
     top target endpoints + source workflows with per-row success rates. All
     computed server-side from webhook_deliveries on page load.
  3. The delivery log — every queued webhook, its full request payload, the
     full response from the endpoint, and a Replay button.

Read/replay data comes from api/workflow/deliveries.php + deliveries_replay.php
(shared with the Workflows nav link, which points here).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [whLatency()](/code/functions/whLatency.md) | 157 | A delivery latency in seconds as translated text (under a second, seconds, minutes or hours); a dash when there is none. |
| [whRate()](/code/functions/whRate.md) | 168 | Success-rate cell: delivered vs terminal (delivered + dead); in-flight ignored. |
| [whAgo()](/code/functions/whAgo.md) | 189 | An age in seconds as translated "n seconds/minutes/hours/days ago" text; empty when there is none. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 69 |  | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |
| 82 |  | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |
| 107 |  | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |
| 133 |  | read [webhook_deliveries](/code/tables/webhook_deliveries.md) |  |
| 143 |  | read [webhook_deliveries](/code/tables/webhook_deliveries.md), read [workflows](/code/tables/workflows.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/theme.php](/code/files/includes/theme.php.md), [system/includes/page_gate.php](/code/files/system/includes/page_gate.php.md), [includes/webhook_delivery.php](/code/files/includes/webhook_delivery.php.md), [system/includes/header.php](/code/files/system/includes/header.php.md)

**Loads scripts:** [assets/js/i18n.js](/code/files/assets/js/i18n.js.md), [assets/js/tz.js](/code/files/assets/js/tz.js.md), [assets/js/mobile.js](/code/files/assets/js/mobile.js.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [pageTitle()](/code/functions/pageTitle.md), [requestScheme()](/code/functions/requestScheme.md), [settingsGetDecrypted()](/code/functions/settingsGetDecrypted.md), [t()](/code/functions/t.md), [webhookEncryptionAvailable()](/code/functions/webhookEncryptionAvailable.md), [webhookPayloadRetentionDays()](/code/functions/webhookPayloadRetentionDays.md)

