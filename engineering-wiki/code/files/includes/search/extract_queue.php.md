# includes/search/extract_queue.php

> PHP - 264 lines

The pending-extraction queue (discussion #53, tier 2).

WHY A QUEUE AT ALL
------------------
Tier 1 reads a `.docx` in milliseconds, so it happens inline. Tier 2 does not:
OCR on a scanned document takes seconds to minutes per page. Doing that inside
the mailbox poll would stall every ticket queued behind it, and inside a web
request it would simply time out.

So an attachment that needs the external extractor is recorded as `pending`
and read later. `pending` is also what a REACHABILITY failure writes — see
`tikaExtract()` — because "the service was down for five minutes" and "this file
cannot be read" are different facts and must not share a status.

TWO WAYS IT DRAINS, EACH WITH ITS OWN SWITCH
--------------------------------------------
  cron           the real answer for a real install: cron/attachment_extract.php
  opportunistic  a few items whenever somebody is already using FreeITSM

Both exist because a cron-only design does nothing at all on an installation
that has not set one up — which includes every evaluation, and any host that
does not offer cron. Opportunistic draining means it works out of the box;
the cron means it keeps up under load. Either can be switched off in
Tickets → Settings → Indexing if it misbehaves.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [extractQueueSettingOn()](/code/functions/extractQueueSettingOn.md) | 48 | Read a boolean setting that defaults to ON when absent. |
| [extractQueueDepth()](/code/functions/extractQueueDepth.md) | 61 | How many attachments are waiting. |
| [extractQueueDrain()](/code/functions/extractQueueDrain.md) | 85 | Read up to `$limit` pending attachments. |
| [extractQueueDepthUnchanged()](/code/functions/extractQueueDepthUnchanged.md) | 219 | Did the attachments we just tried stay pending? If so the extractor is unreachable again and there is no point continuing this pass. |
| [extractQueueDrainOpportunistic()](/code/functions/extractQueueDrainOpportunistic.md) | 239 | A few items, on the back of a request somebody made anyway. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 | `extractQueueSettingOn` | read [system_settings](/code/tables/system_settings.md) |  |
| 63 | `extractQueueDepth` | read [attachment_text](/code/tables/attachment_text.md) |  |
| 107 | `extractQueueDrain` | read [attachment_text](/code/tables/attachment_text.md), read [email_attachments](/code/tables/email_attachments.md) |  |
| 119 | `extractQueueDrain` | **update** [attachment_text](/code/tables/attachment_text.md) (`status`) |  |
| 129 | `extractQueueDrain` | **update** [attachment_text](/code/tables/attachment_text.md) (`status`) |  |
| 137 | `extractQueueDrain` | read [attachment_text](/code/tables/attachment_text.md) |  |
| 159 | `extractQueueDrain` | **update** [attachment_text](/code/tables/attachment_text.md) (`status`, `extracted_datetime`) |  |
| 172 | `extractQueueDrain` | read [attachment_text](/code/tables/attachment_text.md), read [email_attachments](/code/tables/email_attachments.md), read [emails](/code/tables/emails.md) |  |
| 224 | `extractQueueDepthUnchanged` | read [attachment_text](/code/tables/attachment_text.md) |  |

## How it hangs together

**Includes:** [includes/search/indexer.php](/code/files/includes/search/indexer.php.md), [includes/search/tika.php](/code/files/includes/search/tika.php.md)

**Included by:** [api/system/search_status.php](/code/files/api/system/search_status.php.md), [api/system/tika_settings.php](/code/files/api/system/tika_settings.php.md), [cron/attachment_extract.php](/code/files/cron/attachment_extract.php.md)

**Calls:** [searchIndexTicket()](/code/functions/searchIndexTicket.md), [tikaConfigured()](/code/functions/tikaConfigured.md), [tikaHandles()](/code/functions/tikaHandles.md), [tikaTimeoutOverride()](/code/functions/tikaTimeoutOverride.md)

