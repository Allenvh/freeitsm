# cron/calendar_sync_pull.php

> PHP - 110 lines

Calendar sync — read changes back out of analysts' calendars (GH #75).

The outbound half needs no cron: FreeITSM pushes as things change. This is
the inbound half — you move or delete one of these appointments in Outlook
and FreeITSM follows. Latency is roughly your cron interval; every 5 minutes
is sensible.

🔑 A DELTA QUERY, NOT A SUBSCRIPTION. Change notifications need a publicly
reachable HTTPS endpoint for Microsoft to call, plus renewal before it
expires. Plenty of FreeITSM installs are internal only and could never have
that. This is an ordinary outbound GET, so it works behind any firewall.

With a CalDAV server (#133) the same job reads each analyst's calendar with a
sync-collection query, signed in as that analyst. CalDAV has no change
notifications, so for those installs this job is the only way changes come
back; the subscription step below skips them.

⚠️ NOT RUNNING THIS COSTS YOU NOTHING YOU ALREADY HAD. Without it the sync is
one-way, exactly as it shipped. Deleting an event in Outlook then simply means
the next change to that ticket puts a fresh one back.

SECURITY (HTTP invocation only), mirroring the other crons: a shared-secret
token via ?token=<value> matching `webhook_cron_token` in system_settings.
CLI invocation skips it — there is no untrusted caller.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 |  | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/calendar_sync/pull.php](/code/files/includes/calendar_sync/pull.php.md)

**Calls:** [calendarAcceptDeletes()](/code/functions/calendarAcceptDeletes.md), [calendarNotifyUrl()](/code/functions/calendarNotifyUrl.md), [calendarSyncEnsureAllSubscriptions()](/code/functions/calendarSyncEnsureAllSubscriptions.md), [calendarSyncPullAll()](/code/functions/calendarSyncPullAll.md), [calendarSyncSchemaReady()](/code/functions/calendarSyncSchemaReady.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md)

